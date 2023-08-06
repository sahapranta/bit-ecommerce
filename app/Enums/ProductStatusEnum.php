<?php


namespace App\Enums;

enum ProductStatusEnum: string
{
    use \App\Traits\EnumHelper;

    case PUBLISHED = 'published';
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
}
