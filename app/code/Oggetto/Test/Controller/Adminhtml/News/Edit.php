<?php

namespace Oggetto\Test\Controller\Adminhtml\News;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Oggetto\Test\Model\NewsFactory;
use Oggetto\Test\Model\NewsRepositoryFactory;

/**
 * Edit News action.
 */
class Edit extends \Oggetto\Test\Controller\Adminhtml\News implements HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $newsFactory;

    protected $newsRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param NewsFactory $newsFactory
     * @param NewsRepositoryFactory $newsRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        NewsFactory $newsFactory,
        NewsRepositoryFactory $newsRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->newsFactory = $newsFactory;
        $this->newsRepository = $newsRepository;
        parent::__construct($context);
    }

    /**
     * Edit news
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('news_id');
        $model = $this->newsFactory->create();
        $news = $this->newsRepository->create();

        if ($id) {
            $model = $news->getById($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This news no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit News') : __('New News'),
            $id ? __('Edit News') : __('New News')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('News'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New News'));
        return $resultPage;
    }
}
