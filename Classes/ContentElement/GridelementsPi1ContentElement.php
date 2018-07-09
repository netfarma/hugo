<?php

namespace SourceBroker\Hugo\ContentElement;

use SourceBroker\Hugo\Domain\Repository\Typo3ContentRepository;
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
        /** @var Typo3ContentRepository $typo3PageRepository */
        $typo3ContentRepository = $objectManager->get(Typo3ContentRepository::class);

        //pobieramy wszystkie gridy z content page
        $gridElements =  $typo3ContentRepository->getGridElementsByPage($pagePid);

        $nodesByColumn = [];

        //dla kazdego grida wyszykujemy contenty
        foreach($gridElements as $gridElement){
            $contentElements = $typo3ContentRepository->getContainerGridContentElements($gridElement['uid']);


            foreach ($contentElements as $childNode) {
                var_dump($childNode['tx_gridelements_columns']);
                $nodesByColumn[$childNode['tx_gridelements_columns']][] = $childNode['uid'];

//                $flexFormData = GeneralUtility::makeInstance(FlexFormService::class)
//                    ->convertFlexFormContentToArray($contentElementRawData['pi_flexform']);
            }
        }

        $columns = [];
        $columnsCount = $layoutConfig['config']['colCount'];


        var_dump($nodesByColumn);

        foreach ($nodesByColumn as $columnId => $contentElements) {

            $columns['col' . $columnId] = [
                'classes' => '', //TODO skąd mam to pobrać?
                'contentElements' => !empty($contentElements) ? implode(',', $contentElements) : ''
            ];
        }

        $result = [
            'type' => 'grid' . $columnsCount . 'col',
            'columns' => $columns
        ];

       var_dump($result);

        return $result;
    }
}