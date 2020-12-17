<?php /** @noinspection PhpMultipleClassesDeclarationsInOneFile */

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day16;

use MueR\AdventOfCode2020\AbstractSolver;

class Day16 extends AbstractSolver
{
    private array $fields;
    private array $myTicket;
    private array $tickets;

    public function __construct()
    {
        parent::__construct();

        $this->input = explode("\n\n", $this->input);
        $this->fields = array_map(function ($line) {
            $valid = preg_match('/^(.*): (\d+)-(\d+) or (\d+)-(\d+)$/', $line, $match);
            if (!$valid) {
                return null;
            }
            return new Field($match[1], [
                new Rule((int)$match[2], (int)$match[3]),
                new Rule((int)$match[4], (int)$match[5]),
            ]);
        }, explode("\n", $this->input[0]));

        $this->myTicket = explode("\n", $this->input[1]);
        $this->myTicket = array_map(fn ($input) => (int)$input, explode(',', $this->myTicket[1]));

        $this->tickets = array_map(
            fn ($line) => array_map(fn ($input) => (int)$input, explode(',', $line)),
            array_slice(explode("\n", $this->input[2]), 1)
        );
    }

    public function partOne(): int
    {
        return array_sum(array_map(function ($ticket) {
            return array_sum(array_map(fn ($value) => empty(array_filter($this->fields, fn ($field) => $this->isValid($field, $value))) ? $value : 0, $ticket));
        }, $this->tickets));
    }

    public function partTwo(): int
    {
        $validTickets = array_filter($this->tickets, function ($ticket) {
            foreach ($ticket as $value) {
                $matchingRules = array_filter($this->fields, fn ($field) => $this->isValid($field, $value));
                if (empty($matchingRules)) {
                    return false;
                }
            }
            return true;
        });
        array_unshift($validTickets, $this->myTicket);
        $columns = count($this->myTicket);
        $numberOfTickets = count($validTickets);
        $possibleMatches = [];
        for ($i = 0; $i < $columns; $i++) {
            foreach ($this->fields as $field) {
                foreach ($validTickets as $ticket) {
                    $valid = array_filter($field->rules, fn (Rule $rule) => $ticket[$i] >= $rule->min && $ticket[$i] <= $rule->max);
                    if (empty($valid)) {
                        continue 2;
                    }
                }
                $possibleMatches[$field->name][] = $i;
            }
        }

        uasort($possibleMatches, fn ($a, $b) => count($a) === count($b) ? 0 : (count($a) > count($b) ? 1 : -1));

        $certainMatches = [];
        while (count($certainMatches) !== $columns) {
            foreach ($possibleMatches as $name => $options) {
                if (1 !== count($options)) {
                    continue;
                }
                $certainMatches[$name] = array_shift($options);
                unset($possibleMatches[$name]);
                foreach ($possibleMatches as $i => $match) {
                    $possibleMatches[$i] = array_filter($match, static fn ($val) => $val !== $certainMatches[$name]);
                }
            }
        }

        $columns = array_filter($certainMatches, static fn (string $name) => str_starts_with($name, 'departure'), ARRAY_FILTER_USE_KEY);

        return array_product(array_filter($this->myTicket, static fn ($index) => in_array($index, $columns, true), ARRAY_FILTER_USE_KEY));
    }

    private function isValid(Field $field, int $value): bool
    {
        return !empty(array_filter($field->rules, fn (Rule $rule) => $value >= $rule->min && $value <= $rule->max));
    }
}

class Field
{
    /**
     * @param Rule[] $rules
     */
    public function __construct(public string $name, public array $rules)
    {
    }
}
class Rule
{
    public function __construct(public int $min, public int $max)
    {
    }
}
