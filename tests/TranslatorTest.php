<?php namespace Arcanedev\LaravelLang\Tests;

use Illuminate\Translation\Translator;

/**
 * Class     Test
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TranslatorTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Translator */
    private $translator;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->translator = $this->app['translator'];
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->translator);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Translator::class, $this->translator);
    }

    /** @test */
    public function it_can_translate()
    {
        $expectations = [
            'es' => [
                'auth.failed' => 'Estas credenciales no coinciden con nuestros registros.'
            ],
            'fr' => [
                'auth.failed' => 'Ces identifiants ne correspondent pas à nos enregistrements'
            ],
            'it' => [
                'auth.failed' => 'Credenziali non corrispondenti ai dati registrati.'
            ],
        ];

        foreach ($expectations as $locale => $translations) {
            $this->translator->setLocale($locale);

            foreach ($translations as $key => $expected) {
                $this->assertEquals($expected, $this->translator->get($key));
            }
        }
    }
}
