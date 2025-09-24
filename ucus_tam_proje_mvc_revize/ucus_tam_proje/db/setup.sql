
ALTER TABLE IF EXISTS public.flights
    ADD COLUMN IF NOT EXISTS departure_airport_id integer,
    ADD COLUMN IF NOT EXISTS arrival_airport_id integer,
    ADD COLUMN IF NOT EXISTS departure_ts timestamp,
    ADD COLUMN IF NOT EXISTS arrival_ts timestamp;

DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema='public' AND table_name='flights' AND column_name='departure_date') THEN
        UPDATE public.flights
        SET departure_ts = COALESCE(departure_ts, (departure_date::timestamp + departure_time));
    END IF;
    IF EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema='public' AND table_name='flights' AND column_name='arrival_date') THEN
        UPDATE public.flights
        SET arrival_ts = COALESCE(arrival_ts, (arrival_date::timestamp + arrival_time));
    END IF;
END$$;

UPDATE public.flights SET departure_airport_id = COALESCE(departure_airport_id, airport_id)
WHERE airport_id IS NOT NULL AND (departure_airport_id IS NULL);

ALTER TABLE public.flights
    ALTER COLUMN departure_airport_id SET NOT NULL,
    ALTER COLUMN arrival_airport_id SET NOT NULL,
    ALTER COLUMN plane_id SET NOT NULL,
    ALTER COLUMN departure_ts SET NOT NULL,
    ALTER COLUMN arrival_ts SET NOT NULL;

ALTER TABLE public.flights
    ADD CONSTRAINT fk_dep_airport FOREIGN KEY (departure_airport_id) REFERENCES public.airport(id) ON DELETE RESTRICT,
    ADD CONSTRAINT fk_arr_airport FOREIGN KEY (arrival_airport_id) REFERENCES public.airport(id) ON DELETE RESTRICT,
    ADD CONSTRAINT fk_plane FOREIGN KEY (plane_id) REFERENCES public.plane(id) ON DELETE RESTRICT;

ALTER TABLE public.flights
    ADD CONSTRAINT chk_time_order CHECK (arrival_ts > departure_ts);

CREATE OR REPLACE FUNCTION public._overlaps(ts1_start timestamp, ts1_end timestamp, ts2_start timestamp, ts2_end timestamp)
RETURNS boolean LANGUAGE sql IMMUTABLE AS $$
SELECT ts1_start < ts2_end AND ts2_start < ts1_end;
$$;

CREATE OR REPLACE FUNCTION public.trg_plane_overlap()
RETURNS trigger LANGUAGE plpgsql AS $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM public.flights f
        WHERE f.plane_id = NEW.plane_id
          AND f.id <> COALESCE(NEW.id, -1)
          AND public._overlaps(NEW.departure_ts, NEW.arrival_ts, f.departure_ts, f.arrival_ts)
    ) THEN
        RAISE EXCEPTION 'Aynı uçak aynı zaman aralığında birden fazla uçuş yapamaz.';
    END IF;
    RETURN NEW;
END;
$$;

CREATE OR REPLACE FUNCTION public.trg_runway_capacity()
RETURNS trigger LANGUAGE plpgsql AS $$
DECLARE dep_ops int; arr_ops int; dep_pist int; arr_pist int;
BEGIN
    SELECT pist_sayisi INTO dep_pist FROM public.airport WHERE id = NEW.departure_airport_id;
    SELECT pist_sayisi INTO arr_pist FROM public.airport WHERE id = NEW.arrival_airport_id;

    SELECT COUNT(*) INTO dep_ops FROM public.flights f
      WHERE f.departure_airport_id = NEW.departure_airport_id
        AND f.id <> COALESCE(NEW.id,-1)
        AND public._overlaps(NEW.departure_ts, NEW.arrival_ts, f.departure_ts, f.arrival_ts);

    SELECT COUNT(*) INTO arr_ops FROM public.flights f
      WHERE f.arrival_airport_id = NEW.arrival_airport_id
        AND f.id <> COALESCE(NEW.id,-1)
        AND public._overlaps(NEW.departure_ts, NEW.arrival_ts, f.departure_ts, f.arrival_ts);

    IF dep_ops >= dep_pist THEN
        RAISE EXCEPTION 'Kalkış havalimanında eşzamanlı operasyon sayısı pist sayısını aşıyor.';
    END IF;
    IF arr_ops >= arr_pist THEN
        RAISE EXCEPTION 'Varış havalimanında eşzamanlı operasyon sayısı pist sayısını aşıyor.';
    END IF;
    RETURN NEW;
END;
$$;

CREATE OR REPLACE FUNCTION public.trg_airport_capacity()
RETURNS trigger LANGUAGE plpgsql AS $$
DECLARE dep_conc int; arr_conc int; dep_cap int; arr_cap int;
BEGIN
    SELECT ucak_kapasitesi INTO dep_cap FROM public.airport WHERE id = NEW.departure_airport_id;
    SELECT ucak_kapasitesi INTO arr_cap FROM public.airport WHERE id = NEW.arrival_airport_id;

    SELECT COUNT(*) INTO dep_conc FROM public.flights f
      WHERE (f.departure_airport_id = NEW.departure_airport_id OR f.arrival_airport_id = NEW.departure_airport_id)
        AND f.id <> COALESCE(NEW.id,-1)
        AND public._overlaps(NEW.departure_ts, NEW.arrival_ts, f.departure_ts, f.arrival_ts);

    SELECT COUNT(*) INTO arr_conc FROM public.flights f
      WHERE (f.departure_airport_id = NEW.arrival_airport_id OR f.arrival_airport_id = NEW.arrival_airport_id)
        AND f.id <> COALESCE(NEW.id,-1)
        AND public._overlaps(NEW.departure_ts, NEW.arrival_ts, f.departure_ts, f.arrival_ts);

    IF dep_conc >= dep_cap THEN
        RAISE EXCEPTION 'Kalkış havalimanında eşzamanlı uçak sayısı kapasiteyi aşıyor.';
    END IF;
    IF arr_conc >= arr_cap THEN
        RAISE EXCEPTION 'Varış havalimanında eşzamanlı uçak sayısı kapasiteyi aşıyor.';
    END IF;
    RETURN NEW;
END;
$$;

CREATE OR REPLACE FUNCTION public.trg_person_overlap()
RETURNS trigger LANGUAGE plpgsql AS $$
DECLARE has_overlap boolean;
BEGIN
    SELECT EXISTS (
        SELECT 1
        FROM public.flight_person fp
        JOIN public.flights f ON f.id = fp.flight_id
        JOIN public.flights nf ON nf.id = NEW.flight_id
        WHERE fp.person_id = NEW.person_id
          AND public._overlaps(f.departure_ts, f.arrival_ts, nf.departure_ts, nf.arrival_ts)
    ) INTO has_overlap;

    IF has_overlap THEN
        RAISE EXCEPTION 'Aynı kişi aynı zaman aralığında birden fazla uçuş yapamaz.';
    END IF;
    RETURN NEW;
END;
$$;

CREATE TRIGGER trg_flights_plane_overlap
BEFORE INSERT OR UPDATE ON public.flights
FOR EACH ROW EXECUTE FUNCTION public.trg_plane_overlap();

CREATE TRIGGER trg_flights_runway_capacity
BEFORE INSERT OR UPDATE ON public.flights
FOR EACH ROW EXECUTE FUNCTION public.trg_runway_capacity();

CREATE TRIGGER trg_flights_airport_capacity
BEFORE INSERT OR UPDATE ON public.flights
FOR EACH ROW EXECUTE FUNCTION public.trg_airport_capacity();

CREATE TRIGGER trg_person_overlap
BEFORE INSERT ON public.flight_person
FOR EACH ROW EXECUTE FUNCTION public.trg_person_overlap();

DO $$
    BEGIN
        PERFORM setval('public.airport_id_seq',       COALESCE((SELECT MAX(id) FROM public.airport), 0), true);
        PERFORM setval('public.plane_id_seq',         COALESCE((SELECT MAX(id) FROM public.plane),   0), true);
        PERFORM setval('public.person_id_seq',        COALESCE((SELECT MAX(id) FROM public.person),  0), true);
        PERFORM setval('public.flights_id_seq',       COALESCE((SELECT MAX(id) FROM public.flights), 0), true);
        PERFORM setval('public.flight_person_id_seq', COALESCE((SELECT MAX(id) FROM public.flight_person), 0), true);
    END$$;