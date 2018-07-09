<?php

namespace SourceBroker\Hugo\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Typo3ContentRepository
{
    /**
     * @return array
     */
    public function getAll()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
        return $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->orderBy('sorting')
            ->execute()
            ->fetchAll();
    }

    /**
     * @param int $uid
     * @return array
     */
    public function getByUid(int $uid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
        return $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('uid',
                    $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetch();
    }


    public function getGridElementsByPage(int $pageUid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        return $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('pid',
                    $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq('CType',
                    $queryBuilder->createNamedParameter('tx_gridelements_container', \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq('sys_language_uid',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->orderBy('sorting')
            ->execute()
            ->fetchAll();
    }

    /**
     * @param int $gridUid
     * @return array
     */
    public function getContainerGridContentElements(int $gridUid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        return $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('tx_gridelements_container',
                    $queryBuilder->createNamedParameter($gridUid, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq('CType',
                    $queryBuilder->createNamedParameter('text', \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq('sys_language_uid',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->orderBy('sorting')
            ->execute()
            ->fetchAll();
    }

}