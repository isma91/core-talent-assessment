<?php

namespace Base;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Translate;

/**
 * Class TranslateTest
 */
class TranslateTest extends TestCase
{
    private $translate;

    /**
     * TranslateTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->translate = new Translate();
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @dataProvider getIsSupported
     * @param string $sourceLanguage
     * @param string $targetLanguage
     * @param bool $expected
     */
    public function testIsSupported(string $sourceLanguage, string $targetLanguage, bool $expected)
    {
        $this->assertEquals($this->translate->isSupported($sourceLanguage, $targetLanguage), $expected);
    }

    /**
     * @dataProvider getToTranslate
     * @param string $sentence
     * @param string $sourceLanguage
     * @param string $targetLanguage
     * @param array $expected
     * @param array $glossary
     */
    public function testTranslate(string $sentence, string $sourceLanguage, string $targetLanguage, array $expected, array $glossary)
    {
        $translated = $this->translate->translate($sentence, $sourceLanguage, $targetLanguage, $glossary);
        $this->assertContains($translated, $expected);
    }

    public function testTranslateWithBadParams()
    {
        $this->expectExceptionMessage('This language pair is not supported.');
        $this->translate->translate('Hello', 'en', 'zz');

    }

    /**
     * @return array
     */
    public function getIsSupported()
    {
        return [
            ['en', 'fr', true],
            ['en', 'en', true],
            ['aa', 'ht', false],
            ['zz', 'fr', false],
            ['en', 'zz', false]
        ];
    }

    /**
     * @return array
     */
    public function getToTranslate()
    {
        return [
            ['Hello', 'en', 'fr', ['Salut'], []],
            ['Hello Thomas', 'en', 'fr', ['Salut Thomas'], []],
            ['Hello', 'en', 'it', ['Ciao'], []],
            ['<strong class="cl">Hello</strong>', 'en', 'it', ['<strong class="cl">Ciao</strong>'], []],
            ['Hello', 'en', 'it', ['Hello'], [['Hello' => 'Hello']]],
            ['Hello Thomas', 'en', 'fr', ['Bonjour Thomas'], [['Hello' => 'Bonjour']]],
            ['Ceci est mon nom de marque dans une phrase.', 'fr', 'en', ['This is my nom de marque in a sentence.'], [['nom de marque' => 'nom de marque']]]
        ];
    }
}
