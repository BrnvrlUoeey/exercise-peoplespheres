<?php
require './Patterns.php';

/*
 * @todo
 * write string in processed value state
 */

class InputParam
{
    protected bool $isValid = false;
    protected bool $returnMethodResult = false;
    protected string $originalValue;
    protected string $processedValue;

    public function __construct(protected string $name, string $value, protected bool $debug = false)
    {
        $this->originalValue = $value;
        $this->processedValue = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(name $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getOriginalValue(): string
    {
        return $this->originalValue;
    }

    /**
     * @return string
     */
    public function getOriginalValueQuoted(): string
    {
        return addQuotes(spaceLess(strtolower($this->originalValue)));
    }

    /**
     * @param string $value
     */
    public function setOriginalValue(string $value)
    {
        $this->originalValue = $value;
    }

    /**
     * @return string
     */
    public function getProcessedValue(): string
    {
        return $this->processedValue;
    }

    /**
     * @param string $value
     */
    public function setProcessedValue(string $value)
    {
        $this->processedValue = $value;
    }

    protected function resetProcessedValue()
    {
        $this->processedValue = $this->getOriginalValue();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     */
    public function setIsValid(bool $isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @return bool
     */
    public function isReturnMethodResult(): bool
    {
        return $this->returnMethodResult;
    }

    /**
     * @param bool $returnMethodResult
     */
    public function setReturnMethodResult(bool $value): void
    {
        $this->returnMethodResult = $value;
    }

    /*
     * Return words array from value
     */
    protected function getWords(): array
    {
        $this->setReturnMethodResult(false);
        return str_word_count(str_replace('-', ' ', $this->getProcessedValue()), 1);
    }

    /*
     * Return words count from value
     */
    public function wordsCount(): int
    {
        $this->setReturnMethodResult(true);
        return str_word_count(str_replace('-', ' ', $this->getProcessedValue()));
    }

    /*
     * Returns a string concatenating
     * each word x first characters from $this->processedValue.
     */
    public function eachWordFirstChars(int $n): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();

        if ($this->debug) {
            echo "<p>DANS InputParam::eachWordFirstChars()<br />
                 Dump de \$words :<br />";
            var_dump($words);
            echo "</p>";
        }

        $firstChars = array_map(fn($value): string => substr($value, 0, $n), $words);

        if ($this->debug) {
            echo "<p>Dump de \$firstChars :<br />";
            var_dump($firstChars);
            echo "</p>";
        }

        $this->setProcessedValue(implode('', $firstChars));
        return $this;
    }

    /*
     * Returns a string concatenating
     * each word x last characters from $this->processedValue.
     */
    public function eachWordLastChars(int $n): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();
        $lastChars = array_map(fn($value): string => substr($value, -$n), $words);
        $this->setProcessedValue(implode('', $lastChars));
        return $this;
    }

    /*
     * Returns "first" x words from $this->processedValue
     */
    public function firstWords(int $n): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();
        if ($n > 0) {
            $firstWords = array_slice($words, 0, $n); // Keeps $n words from the left
        } elseif ($n < 0) {
            $firstWords = array_slice($words, $n); // Keeps $n words from the right
        }
        $this->setProcessedValue(implode(' ', $firstWords));
        return $this;
    }

    /*
     * Returns "last" x words from $this->processedValue
     */
    public function lastWords(int $n): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();
        if ($n > 0) {
            $lastWords = array_slice($words, count($words) - ($n + 1)); // Skip $n first words
        } elseif ($n < 0) {
            $lastWords = array_slice($words, 0, count($words) - abs($n)); // Skip $n last words
        }
        $this->setProcessedValue(implode(' ', $lastWords));
        return $this;
    }

    public function dispatch(array $matches)
    {
        global $offset;
        $this->resetProcessedValue();

        if ($this->debug) {
            echo "<p>";
            echo "Dump de \$matches dans InputParam::dispatch()<br />";
            var_dump($matches);
            echo "<br /><br />Dump de \$this->getProcessedValue() BEFORE dans InputParam::dispatch()<br />";
            echo "<br />";
            var_dump($this->getProcessedValue());
            echo "<br />";
        }

        $chain = $matches[0][0];
        $inputIndex = $matches['input'][0];
        $method = $matches['method'][0];
        $parameter = $matches['parameter'][0];

        // Single method call, execute it
        if (strlen($chain) <= strlen($method) + strlen($parameter) + 9) {

            echo ifDebug('<p>SINGLE CALL<br />');
            echo ifDebug("\$method = $method<br />");
            echo ifDebug("\$parameter = $parameter</p>");

            $result = $this->$method($parameter);
        }
        // Chained methods calls, decompose for sequential execution
        else {

            echo ifDebug('<p>CHAINED CALLS</p>');

            $calls = ['chain' => $chain,
                    'inputIndex' => $inputIndex,
                    'last_method_matched' => $method,
                    'last_parameter_matched' => $parameter];
            $result = $this->chainedCall($calls);
        }

        if ($this->debug) {
            echo "<br /><br />Dump de \$this->getProcessedValue() / \$result AFTER dans InputParam::dispatch()<br />";
            echo "<br />";
            var_dump($result);
            echo "<br />";
            var_dump($this->getProcessedValue());
            echo "</p>";
        }

        // For methods that directly return evaluated result in expression
        if ($this->isReturnMethodResult()) {
            // update global offset position : pass method call position + replacement expression length
            $offset = $matches[0][1] + strlen($result);
            return $result;

        // For methods that alter $this->ProcessedValue before it is returned
        } else {
            $processedValue = $this->getProcessedValue();
            // update global offset position : pass method call position + replacement expression length
            $offset = $matches[0][1] + strlen($processedValue);
            return addQuotes(spaceLess(strtolower($processedValue)));
        }
    }

    protected function chainedCall(array $calls)
    {
        if ($this->debug) {
            echo "<hr /><p>Dump de \$this->getProcessedValue() dans InputParam::chainedCall()<br />";
            echo "<br />";
            var_dump($this->getProcessedValue());
            echo "</p>";
            echo "<hr /><p>Dump de \$this->getWords() dans InputParam::chainedCall()<br />";
            echo "<br />";
            var_dump($this->getWords());
            echo "</p>";
        }

        $return = preg_match_all('#'. Patterns::METHOD_CALL_PATTERN .'#', $calls['chain'], $matches);

        if ($return > 0) {

            if ($this->debug) {
                echo "<hr /><p>";
                echo "Dump de \$matches dans InputParam::chainedCall()<br />";
                var_dump($matches);
                echo "</p>";
            }

            foreach ($matches['method'] as $index => $method) {

                $parameter = $matches['parameter'][$index];

                echo ifDebug("<p>\$index = $index<br />");
                echo ifDebug("\$method = $method<br />");
                echo ifDebug("\$parameter = $parameter</p>");

                $result = $this->$method($parameter);

                if ($this->debug) {
                    echo "<hr /><p>Dump de \$this->getProcessedValue() dans InputParam::chainedCall()<br />";
                    echo "<br />";
                    var_dump($this->getProcessedValue());
                    echo "</p>";
                    echo "<hr /><p>Dump de \$this->getWords() dans InputParam::chainedCall()<br />";
                    echo "<br />";
                    var_dump($this->getWords());
                    echo "</p>";
                }
            }
            return $result;
        }
    }

    public function __toString():string
    {
        return $this->getProcessedValue();
    }

}