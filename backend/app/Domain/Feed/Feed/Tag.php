<?php

namespace App\Domain\Feed\Feed;

class Tag
{

    public function __construct(
        /**
         * Name of the tag
         *
         * Atom: <category><term>
         * RSS: <item><category>
         * JSON Feed: item.tags[]
         */
        public string $name,
    ) {
    }

}
