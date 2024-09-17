<?php
declare(strict_types=1);

namespace CakeDC\SearchFilter\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected $fixtures = [
        'plugin.CakeDC/SearchFilter.Articles',
        'plugin.CakeDC/SearchFilter.Authors',
    ];

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex(): void
    {
        $this->get('/articles');
        $this->assertResponseOk();
        $this->assertResponseContains('Articles');
    }

    public function testString(): void
    {
        $this->get('/articles?f[0]=title&c[0]=like&v[0][value][]=First');
        $this->assertResponseOk();
        $this->assertResponseContains('First Article');
        $this->assertResponseNotContains('Second Article');
    }

    public function testAuthorLike(): void
    {
        $this->get('/articles?f[0]=author_id&c[0]=like&v[0][value][]=larry');
        $this->assertResponseOk();
        $this->assertResponseContains('Second Article');
        $this->assertResponseNotContains('First Article');
    }

    public function testAuthorEqual(): void
    {
        $this->get('/articles?f[0]=author_id&c[0]=%3D&v[0][id][]=1');
        $this->assertResponseOk();
        $this->assertResponseContains('First Article');
        $this->assertResponseNotContains('Second Article');
    }

    public function testCreatedDate(): void
    {
        $this->get('/articles?f[0]=created&c[0]=between&v[0][from]=2023-01-01T00:00&v[0][to]=2023-12-31T00:00');
        $this->assertResponseOk();
        $this->assertResponseContains('First Article');
        $this->assertResponseNotContains('Old Article');
    }

    public function testCombinedFilters(): void
    {
        $this->get('/articles?f[0]=title&c[0]=like&v[0][value][]=First&f[1]=created&c[1]=greaterOrEqual&v[1][value][]=2023-01-01T00:00');
        $this->assertResponseOk();
        $this->assertResponseContains('First Article');
        $this->assertResponseNotContains('Second Article');
        $this->assertResponseNotContains('Old Article');
    }
}
