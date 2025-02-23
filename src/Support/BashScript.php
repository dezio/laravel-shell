<?php
/**
 * File: BashScript.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Support;

use Closure;

class BashScript
{
    private array $variables = [];
    private array $statements;

    public function __construct()
    {
        $this->statements = [];
    }

    public function addVariable(string $name, Closure $value): self
    {
        $this->variables[$name] = $value;
        return $this;
    }

    public function addStatement(string $statement): self
    {
        $this->statements[] = $statement;
        return $this;
    }

    public function if(string $condition, Closure $then, Closure $else)
    {
        $thenStatements = value($then);
        $elseStatements = value($else);
        $this->statements[] = "if [ $condition ]; then";
        foreach ($thenStatements as $thenStatement) {
            $this->statements[] = $thenStatement;
        }
        $this->statements[] = "else";
        foreach ($elseStatements as $elseStatement) {
            $this->statements[] = $elseStatement;
        }
        if (empty($elseStatements)) {
            $this->statements[] = ":";
        }
        $this->statements[] = "fi";
    }

    public function getScript(): string
    {
        $header = [];
        foreach ($this->variables as $name => $value) {
            $value1 = value($value);
            if(is_string($value1)) {
                $value1 = "'$value1'";
            }

            $header[] = "$name=" . $value1;
        }
        $str = [];
        if (!empty($header)) {
            $str[] = implode("\n", $header);
        }
        $body = $this->getBody();
        if (!empty($body)) {
            $str[] = implode("\n", $body);
        }

        return implode("\n", $str);
    }

    private function getBody()
    {
        $body = [];
        foreach ($this->statements as $statement) {
            $body[] = $statement;
        }
        return $body;
    }
}
