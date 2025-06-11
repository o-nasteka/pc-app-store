<?php

declare(strict_types=1);

namespace App\Application\DTO\Auth;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    public string $name;

    public string $ipAddress;
    public ?string $userAgent;

    public function __construct(
        string $email,
        string $password,
        string $name,
        string $ipAddress,
        ?string $userAgent = null
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }
}
