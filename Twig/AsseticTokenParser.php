<?php

namespace Oro\Bundle\NavigationBundle\Twig;

use Assetic\Asset\AssetInterface;
use Assetic\Factory\AssetFactory;
use Symfony\Bundle\AsseticBundle\Twig\AsseticNode;


class AsseticTokenParser extends \Twig_TokenParser
{
    /**
     * @var array
     */
    private $assets;

    /**
     * @var \Assetic\Factory\AssetFactory
     */
    private $factory;

    private $tag;

    private $output;

    /**
     * @param array        $assets
     * @param AssetFactory $factory
     * @param              $tag
     * @param              $output
     */
    public function __construct(array $assets, AssetFactory $factory, $tag, $output)
    {
        $this->assets = $assets;
        $this->factory = $factory;
        $this->tag = $tag;
        $this->output = $output;
    }

    /**
     * {@inheritDoc}
     */
    public function parse(\Twig_Token $token)
    {
        $inputs = $this->assets;

        $filters = array();
        $attributes = array(
            'output'   => $this->output,
            'var_name' => 'asset_url',
            'vars'     => array(),
        );

        $stream = $this->parser->getStream();

        while (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->test(\Twig_Token::NAME_TYPE, 'filter')) {
                $filters = array_merge(
                    $filters,
                    array_filter(array_map('trim', explode(',', $this->parseValue($stream, false))))
                );
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'output')) {
                $attributes['output'] = $this->parseValue($stream, false);
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'debug')) {
                $attributes['debug'] = $this->parseValue($stream);
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'combine')) {
                $attributes['combine'] = $this->parseValue($stream);
            } else {
                $token = $stream->getCurrent();
                throw new \Twig_Error_Syntax(sprintf(
                    'Unexpected token "%s" of value "%s"',
                    \Twig_Token::typeToEnglish($token->getType(), $token->getLine()),
                    $token->getValue()
                ), $token->getLine());
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'testEndTag'), true);
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $name = $this->factory->generateAssetName($inputs, $filters, $attributes);

        return new AsseticNode(
            $this->factory->createAsset($inputs, $filters, $attributes + array('name' => $name)),
            $body,
            $inputs,
            $filters,
            $name,
            $attributes,
            $token->getLine(),
            $this->getTag()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Test for end tag
     *
     * @param \Twig_Token $token
     *
     * @return bool
     */
    public function testEndTag(\Twig_Token $token)
    {
        return $token->test(array('end' . $this->tag));
    }

    /**
     * Get value from stream
     *
     * @param \Twig_TokenStream $stream
     * @param bool              $isBool
     *
     * @return bool|string
     */
    protected function parseValue(\Twig_TokenStream $stream, $isBool = true)
    {
        $stream->next();
        $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');

        if ($isBool) {
            return 'true' == $stream->expect(\Twig_Token::NAME_TYPE, array('true', 'false'))->getValue();
        }

        return $stream->expect(\Twig_Token::STRING_TYPE)->getValue();
    }
}
