Changelog
---------

0.0.13
~~~~~~

1) [FEATURE] Add "parent" property to menus.


0.0.12
~~~~~~

1) [FEATURE] Add special "warning" content element to pass some info from exporter.

0.0.11
~~~~~~

1) [FEATURE] Add info about content elements in each column.

0.0.10
~~~~~~

1) [BUGFIX] Add missing wrappers '---' for yaml -media files.
2) [FEATURE] Add symlink for storage fodler to hugo media folder.

0.0.9
~~~~~

1) [BUGFIX] Leave site roots foreach after first hugo enabled site root because content elements are the same for all
    root sites.
2) [FEATURE] Initial implementation for media export.
3) [FEATURE] Add possibility to overwrite DCE elements default CType namings in hugo export to have more meaning
   in partial namings.

0.0.8
~~~~~

1) [BUGFIX] Create directory for data/content if not exists yet.
2) [BUGFIX] Add missing TsConfig for content elements exporter.

0.0.7
~~~~~
1) [TASK] Move getSiteRootPages() from service class to Typo3PageRepository.
   Replace GeneralUtility::makeInstance with ObjectManager->get()
2) [TASK] Refactor for better naming for future content / media exporters.
3) [FEATURE] Init version for content element exporter. So far it works only from
    cli level and for header / text and dce content elements.

0.0.6
~~~~~
1) [FEATURE] Run hugo build after export tree finish. Init verison to be improved.

0.0.5
~~~~~
1) [TASK] Rename PageTraverser to TreeTraverser
2) [TASK] Rename metaData to frontMatter in Document class.
3) [TASK] Add support to disable tree export - its possible now to now export for some site root trees.
4) [TASK] Rename values of hugo menu identifiers in TYPO3.
5) [TASK] Add simple support for menu (assign menu, menu identifier, add weight)
6) [TASK] Make YamlWriter->clean() to be more safe in case wrong set of path to store.
7) [TASK] Exclude media folder from cleaning by Writer calss.
8) [FEATURE] Add DataHanler support for events in TYPO3 like add / delete / move page to regenerate content.

0.0.4
~~~~~
1) [BUGFIX] Fix lacking YAML "---" separator in md file / fix wrong extension for yaml writer.
2) [TASK] Remove auto finding for root page in Configurator / refactor Configurator class.
3) [FEATURE] Introduce support for multi site root. Each of site root should have own configuration of writer pathes
    to export content to separate folders.
4) [BUGFIX] Remove not needed ImageoptCommandController scheduler task init.
5) [TASK] Add folders to ignore after installing vendors.
6) [TASK] Add dummy "layout" value for having beginning working solution.

0.0.3
~~~~~
1) [TASK] Add cocur/slugify dependency.

0.0.2
~~~~~
1) [TASK] Add composer.json file.
2) [DOCS] Docs fixes.

0.0.1
~~~~~
1) Init version.
