<?php

namespace Oggetto\Test\Controller\Adminhtml\News;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Oggetto\Test\Model\NewsRepositoryFactory;

/**
 * Delete News action.
 */
class Delete extends \Oggetto\Test\Controller\Adminhtml\News implements HttpPostActionInterface
{
    protected $newsRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param NewsRepositoryFactory $newsRepository
     */
    public function __construct(
        Context $context,
        NewsRepositoryFactory $newsRepository
    ) {
        $this->newsRepository = $newsRepository;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('news_id');
        if ($id) {
            try {
                $news = $this->newsRepository->create();
                $news->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the news.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['news_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a news to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
