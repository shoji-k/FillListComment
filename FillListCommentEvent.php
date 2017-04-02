<?php
/*
 * This file is part of the FillListComment
 *
 * Copyright (C) 2017 freks
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FillListComment;

use Eccube\Application;
use Eccube\Event\TemplateEvent;
use Eccube\Event\EventArgs;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class FillListCommentEvent
{
    /**
     * @var \Eccube\Application $app
     **/
    private $app;

    /**
     * @var \Eccube\Application $app
     **/
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @var \Eccube\Event\EventArgs $event
     **/
    public function onAdminProductEditInitialize(EventArgs $event)
    {
        // copy detail to list when list is empty
        $request = $event->getRequest();
        $adminProduct = $request->request->get('admin_product');
        if (empty($adminProduct['description_list'])) {
            $adminProduct['description_list'] = $adminProduct['description_detail'];
            $request->request->set('admin_product', $adminProduct);
            $event->setRequest($request);
        }
    }

    /**
     * @param TemplateEvent $event
     **/
    public function onRenderAdminProductProductEditBefore(TemplateEvent $event)
    {
        $source = $event->getSource();
        $source = str_replace(
            '一覧コメントを追加',
            '一覧コメントを追加&nbsp;<small>空で保存すると商品説明をコピーします</small>',
            $source
        );
        $source = $event->setSource($source);
    }
}
