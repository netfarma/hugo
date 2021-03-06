<?php

namespace SourceBroker\Hugo\ContentElement;

class TextContentElement extends AbstractContentElement
{
    /**
     * @param array $contentElementRawData
     * @return array
     */
    public function getSpecificContentElementData(array $contentElementRawData): array
    {
        // TODO: parse links
        return [
            'text' => $contentElementRawData['bodytext'],
        ];
    }
}