<?php

namespace Oggetto\Test\Controller\Adminhtml\News;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Oggetto\Test\Api\NewsRepositoryInterface;
use Oggetto\Test\Model\News;
use Oggetto\Test\Model\NewsFactory;
use Oggetto\Test\Model\News\ImageUploader;

/**
 * Save news action.
 */
class Save extends \Oggetto\Test\Controller\Adminhtml\News implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var NewsFactory
     */
    private $newsFactory;

    /**
     * @var NewsRepositoryInterface
     */
    private $newsRepository;

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param NewsFactory|null $newsFactory
     * @param NewsRepositoryInterface|null $newsRepository
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        NewsFactory $newsFactory,
        NewsRepositoryInterface $newsRepository,
        ImageUploader $imageUploader
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->newsFactory = $newsFactory;
        $this->newsRepository = $newsRepository;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = News::STATUS_ENABLED;
            }
            if (empty($data['news_id'])) {
                $data['news_id'] = null;
            }

            $imageName = '';
            if (!empty($data['image'])) {
                $imageName = $data['image'][0]['name'];
            }
            $data['image'] = $imageName;

            $news = $this->newsFactory->create();

            $id = $this->getRequest()->getParam('news_id');
            if ($id) {
                try {
                    $news = $this->newsRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This news no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $news->setData($data);

            try {
                $this->newsRepository->save($news);
                if ($imageName) {
                    $this->imageUploader->moveFileFromTmp($imageName);
                }
                $this->messageManager->addSuccessMessage(__('You saved the news.'));
                $this->dataPersistor->clear('mage_news');
                return $this->processNewsReturn($news, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the news.'));
            }

            $this->dataPersistor->set('mage_news', $data);
            return $resultRedirect->setPath('*/*/edit', ['news_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the news return
     *
     * @param News $news
     * @param array $data
     * @param ResultInterface $resultRedirect
     * @return ResultInterface
     */
    private function processNewsReturn($news, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['news_id' => $news->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateNews = $this->newsFactory->create(['data' => $data]);
            $duplicateNews->setId(null);
            $duplicateNews->setIsActive(News::STATUS_DISABLED);
            $this->newsRepository->save($duplicateNews);
            $id = $duplicateNews->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the news.'));
            $this->dataPersistor->set('mage_news', $data);
            $resultRedirect->setPath('*/*/edit', ['news_id' => $id]);
        }
        return $resultRedirect;
    }
}
