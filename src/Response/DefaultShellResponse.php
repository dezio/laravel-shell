<?php
/**
 * File: DefaultShellResponse.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Response;

use DeZio\Shell\Contracts\ShellResponse;
use Stringable;

class DefaultShellResponse implements ShellResponse, Stringable
{
    private bool $trimOutput = true;

    private string $output;

    private string $error;

    private int $exitCode;

    public function __construct(string $output, string $error, int $exitCode)
    {
        $this->output = $output;
        $this->error = $error;
        $this->exitCode = $exitCode;
    }

    public function trimOutput(bool $trimOutput): void
    {
        $this->trimOutput = $trimOutput;
    }

    public function getOutput(): string
    {
        return $this->trimOutput ? trim($this->output) : $this->output;
    }


    public function getError(): string
    {
        return $this->error;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function isSuccess(): bool
    {
        return $this->exitCode === 0;
    }

    public function __toString(): string
    {
        return $this->getOutput();
    }

    public function throw(): ShellResponse
    {
        if ($this->isSuccess()) {
            return $this;
        }

        throw new \Exception($this->getError());
    }
}
