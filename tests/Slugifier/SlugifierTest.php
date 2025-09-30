<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Slugifier;

use Freyr\TDD\Slugifier\Policies\ReplaceHtmlEntitiesPolicy;
use Freyr\TDD\Slugifier\Policies\ReplaceMultipleMinusPolicy;
use Freyr\TDD\Slugifier\Policies\ReplaceNonAZAndNumbersPolicy;
use Freyr\TDD\Slugifier\Policies\ReplacePolishSpecialCharactersPolicy;
use Freyr\TDD\Slugifier\Policies\ReplaceWhitespacesPolicy;
use Freyr\TDD\Slugifier\Policies\StrToLowerPolicy;
use Freyr\TDD\Slugifier\Policies\TrimMinusesPolicy;
use Freyr\TDD\Slugifier\Policies\TrimPolicy;
use Freyr\TDD\Slugifier\PolicyPipeline;
use Freyr\TDD\Slugifier\Slugifier;
use Freyr\TDD\Slugifier\SlugifierInterface;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SlugifierTest extends TestCase
{
    private function buildClass(): SlugifierInterface
    {
        return new Slugifier(
            new PolicyPipeline(
                new TrimPolicy(),
                new StrToLowerPolicy(),
                new ReplacePolishSpecialCharactersPolicy(),
                new ReplaceHtmlEntitiesPolicy(),
                new ReplaceWhitespacesPolicy(),
                new ReplaceNonAZAndNumbersPolicy(),
                new ReplaceMultipleMinusPolicy(),
                new TrimMinusesPolicy()
            )
        );
    }

    public static function slugifyDataProvider(): Generator
    {
        yield 'trim text' => [' abc ', 'abc'];
        yield 'strtolower' => ['ABC', 'abc'];
        yield 'trim and strtolower' => [' ABC ', 'abc'];
        yield 'replace polish characters' => ['ąćęłńóśżź', 'acelnoszz'];
        yield 'remove html entity &nbsp' => ['&nbsp;', ''];
        yield 'remove html entity &copy' => ['&copy;', ''];
        yield 'remove html entity &copy in text' => ['text &copy;', 'text'];
        yield 'replace _ and space with -' => ['a_b c_d', 'a-b-c-d'];
        yield 'remove non a-z0-9 characters' => ['a!!b@@c##d', 'abcd'];
        yield 'replace multiple - with just one' => ['a----b', 'a-b'];
        yield 'remove - from beginning' => ['-abc', 'abc'];
        yield 'remove - from end' => ['abc-', 'abc'];
    }

    #[DataProvider('slugifyDataProvider')]
    public function testSlugify(string $input, string $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            $this->buildClass()->slugify($input)
        );
    }
}
