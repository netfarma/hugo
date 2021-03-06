<?php

/***************************************************************
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace SourceBroker\Hugo\Service;

use SourceBroker\Hugo\Configuration\Configurator;
use SourceBroker\Hugo\Traversing\TreeTraverser;
use TYPO3\CMS\Core\Locking\Exception\LockAcquireWouldBlockException;
use TYPO3\CMS\Core\Locking\LockFactory;
use TYPO3\CMS\Core\Locking\LockingStrategyInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use \SourceBroker\Hugo\Domain\Repository\Typo3PageRepository;

/**
 * Class HugoExportService
 * @package SourceBroker\Hugo\Service
 */
class HugoExportPageService
{
    /**
     * @return bool
     * @throws \TYPO3\CMS\Core\Locking\Exception\LockAcquireException
     * @throws \TYPO3\CMS\Core\Locking\Exception\LockCreateException
     * @throws \Exception
     */
    public function exportAll(): bool
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $lockFactory = $objectManager->get(LockFactory::class);
        $locker = $lockFactory->createLocker(
            'hugoExportPages',
            LockingStrategyInterface::LOCK_CAPABILITY_SHARED | LockingStrategyInterface::LOCK_CAPABILITY_NOBLOCK
        );
        do {
            try {
                $locked = $locker->acquire(LockingStrategyInterface::LOCK_CAPABILITY_SHARED | LockingStrategyInterface::LOCK_CAPABILITY_NOBLOCK);
            } catch (LockAcquireWouldBlockException $e) {
                usleep(100000); //100ms
                continue;
            }
            if ($locked) {
                break;
            }
        } while (true);

        foreach (($objectManager->get(Typo3PageRepository::class))->getSiteRootPages() as $siteRoot) {
            $hugoConfigForRootSite = $objectManager->get(Configurator::class, null, $siteRoot['uid']);
            if ($hugoConfigForRootSite->getOption('enable')) {
                // EXPORT CONTENT FROM TYPO3
                /** @var $hugoConfigForRootSite Configurator */
                $writer = $objectManager->get($hugoConfigForRootSite->getOption('writer.class'));
                $treeTraverser = $objectManager->get(TreeTraverser::class);
                $writer->setRootPath($hugoConfigForRootSite->getOption('writer.path.content'));
                $writer->setExcludeCleaningFolders([$hugoConfigForRootSite->getOption('writer.path.media')]);
                $treeTraverser->setWriter($writer);
                $treeTraverser->start($siteRoot['uid'], []);

                // USE CONTENT EXPORTED FROM TYPO3 TO RUN HUGO BUILD
                $hugoPathBinary = $hugoConfigForRootSite->getOption('hugo.path.binary');
                if (!empty($hugoPathBinary)) {
                    exec($hugoPathBinary . ' ' . str_replace(['{PATH_site}'], [PATH_site],
                            $hugoConfigForRootSite->getOption('hugo.command')));
                } else {
                    throw new \Exception('Can not find hugo binary');
                }
            }
        }
        if ($locked) {
            $locker->release();
            $locker->destroy();
            return true;
        }
    }

}
