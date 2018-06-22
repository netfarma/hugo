<?php

namespace SourceBroker\Hugo\ContentElement;

use SourceBroker\Hugo\Domain\Repository\Typo3PageRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use SourceBroker\Hugo\Configuration\Configurator;

class GridelementsPi1ContentElement extends AbstractContentElement
{
    /**
     * @param array $contentElementRawData
     * @return array
     */
    public function getSpecificContentElementData(array $contentElementRawData): array
    {
        //TODO To jest tylko DRAFT. Chcę wiedzieć czy idę w dobrym kierunku, czy odpowiednie dane są pobierane

        $pagePid = $contentElementRawData['pid'];

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var Configurator $hugoConfig */
        $hugoConfig = $objectManager->get(Configurator::class, null, $pagePid);
        //TODO sugerowany wyżej Configurator nie posiada konfiguracji grifów tx_gridelements
        //TODO próbowałem na wiele sposobów ale dopiero poniższe rozwiązanie pobrało mi konfigurację


        $config = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService')
            ->convertTypoScriptArrayToPlainArray(BackendUtility::getPagesTSconfig($pagePid));

        $layoutConfig = $config['tx_gridelements']['setup'][$contentElementRawData['tx_gridelements_backend_layout']];

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $typo3PageRepository = $objectManager->get(Typo3PageRepository::class);
        $childNodes = $typo3PageRepository->getPageContentElements($pagePid);

        $nodesByColumn = [];

        foreach ($childNodes as $childNode) {
            $nodesByColumn[$childNode['colPos']][] = $childNode['uid'];
        }

        $columns = [];
        $columnsCount = $layoutConfig['config']['colCount'];

        for ($i = 1; $i <= $columnsCount; $i++) {
            $columns['col' . $i] = [
                'classes' => '', //TODO skąd mam to pobrać?
                'contentElements' => !empty($nodesByColumn[$i]) ? implode(',', $nodesByColumn[$i]) : ''
            ];
        }

        $result = [
            'type' => 'grid' . $columnsCount . 'col',
            'columns' => $columns
        ];

        return $result;
    }
}