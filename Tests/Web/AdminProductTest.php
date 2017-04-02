<?php
namespace Eccube\Tests\Web;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;

class AdminProductTest extends AbstractAdminWebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * onAdminProductEditInitialize用
     */
    public function testSaveAdminProduct()
    {
        // 一覧コメントが空の場合、商品説明をコピーする
        $description = 'これは商品説明です';
        $listComment = '';
        $crawler = $this->client->request(
            'POST',
            $this->app->url('admin_product_product_new'), [
                'admin_product' => [
                    'description_detail' => $description,
                ]
            ]
        );
        $form = $crawler->selectButton('商品を登録')->form();
        $values = $form->getPhpValues();
        $this->assertEquals($values['admin_product']['description_detail'], $description);
        $this->assertEquals($values['admin_product']['description_list'], $description);

        // 一覧コメントが入力されている場合、商品説明はコピーしない
        $description = 'これは商品説明です';
        $listComment = 'これはリスト説明です';
        $crawler = $this->client->request(
            'POST',
            $this->app->url('admin_product_product_new'), [
                'admin_product' => [
                    'description_detail' => $description,
                    'description_list' => $listComment,
                ]
            ]
        );
        $form = $crawler->selectButton('商品を登録')->form();
        $values = $form->getPhpValues();
        $this->assertEquals($values['admin_product']['description_detail'], $description);
        $this->assertEquals($values['admin_product']['description_list'], $listComment);
    }

    /**
     * onRenderAdminProductProductEditBefore用
     */
    public function testCommentAdminProductNew()
    {
        $crawler = $this->client->request(
            'GET',
            $this->app->url('admin_product_product_new')
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $text = $crawler->filter('#detail_description_box__list_toggle')->text();
        $this->assertContains('空で保存すると', $text);
    }

    /**
     * onRenderAdminProductProductEditBefore用
     */
    public function testCommentAdminProductEdit()
    {
        $crawler = $this->client->request(
            'GET',
            $this->app->url('admin_product_product_edit', ['id' => 1]),
            []
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $text = $crawler->filter('#detail_description_box__list_toggle')->text();
        $this->assertContains('空で保存すると', $text);
    }
}
