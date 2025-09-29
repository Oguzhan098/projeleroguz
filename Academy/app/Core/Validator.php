<?php
namespace App\Core;

class Validator
{
    private array $data; private array $errors = [];
    public function __construct(array $data) { $this->data = $data; }

    public function required(string $field, string $msg='Zorunlu alan'): self
    {
        $v = trim((string)($this->data[$field] ?? ''));
        if ($v === '') $this->errors[$field] = $msg;
        return $this;
    }

    public function email(string $field, string $msg='Geçerli e-posta giriniz'): self
    {
        $v = trim((string)($this->data[$field] ?? ''));
        if ($v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL)) $this->errors[$field] = $msg;
        return $this;
    }

    public function numeric(string $field, string $msg='Sayısal olmalı'): self
    {
        $v = (string)($this->data[$field] ?? '');
        if ($v !== '' && !is_numeric($v)) $this->errors[$field] = $msg;
        return $this;
    }

    public function errors(): array { return $this->errors; }
}
