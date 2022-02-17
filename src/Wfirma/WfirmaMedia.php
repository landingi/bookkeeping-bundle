<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Exception;
use Landingi\BookkeepingBundle\Bookkeeping\Media;
use RuntimeException;
use SimpleXMLElement;

final class WfirmaMedia implements Media
{
    private SimpleXMLElement $builder;

    public static function api(): self
    {
        return new self(
            new SimpleXMLElement(
                <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
</api>
XML
            )
        );
    }

    public function __construct(SimpleXMLElement $simpleXMLElement)
    {
        $this->builder = $simpleXMLElement;
    }

    public function with(string $key, string $value): self
    {
        try {
            if ('' === $value) {
                $child = $this->builder->addChild($key);
            } else {
                $child = $this->builder->addChild($key, htmlspecialchars($value, ENT_XML1, 'UTF-8'));
            }

            return new self($child);
        } catch (Exception $e) {
            throw new RuntimeException('Invalid XML child value');
        }
    }

    public function toString(): string
    {
        $xml = $this->builder->asXML();

        if (false === $xml) {
            throw new RuntimeException('Invalid XML');
        }

        return $xml;
    }
}
