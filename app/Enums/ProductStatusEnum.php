<?php


namespace App\Enums;

enum ProductStatusEnum: string
{
    use \App\Traits\EnumHelper;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
