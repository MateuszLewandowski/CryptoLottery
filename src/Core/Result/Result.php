<?php 

namespace App\Core\Result;

final class Result 
{
    public function __construct(
        private ?int $code = null,
        private ?string $message = null,
    ) {
    }

    public function getCode(): ?int 
    {
        return $this->code;
    }

    public function getMessage(): ?string 
    {
        return $this->message;
    }

    public function compact(): array 
    {
        return [$this->message, $this->code];
    }

    public function setMessageKey(string $name): void {
        $this->message = str_replace('@key', $name, $this->message);
        return;
    }
}