<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Media;
use RuntimeException;
use SimpleXMLElement;

final class WfirmaMedia implements Media
{
    private SimpleXMLElement $builder;

    public function __construct(SimpleXMLElement $simpleXMLElement)
    {
        $this->builder = $simpleXMLElement;
    }

    public function with(string $key, string $value): self
    {
        if ('' === $value) {
            $child = $this->builder->addChild($key);
        } else {
            $child = $this->builder->addChild($key, $value);
        }

        return new self($child);
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
