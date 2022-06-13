<?php

namespace App\Controller;

use PHPStan\PhpDocParser\Parser\ParserException;
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;
use Seld\JsonLint\Undefined;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * ApiController
 *
 * @author Florian Weber <git@fweber.info>
 */
abstract class ApiController extends AbstractController
{
    /**
     * @param string $json
     * @return bool
     */
    protected function isValidJson(string $json): bool
    {
        $parser = new JsonParser();

        try {
            $parser->lint($json);
        } catch (ParserException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param string $json
     * @return bool|float|int|mixed|array|Undefined|stdClass|string|null
     * @throws ParsingException
     */
    protected function parseJson(string $json)
    {
        $parser = new JsonParser();
        return $parser->parse($json);
    }
}
