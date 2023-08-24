<?php

declare(strict_types=1);

namespace Lyrica0954\JapaneseBlock\block;

use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Opaque;
use pocketmine\block\utils\Fallable;
use pocketmine\block\utils\FallableTrait;

class JapaneseBlock extends Opaque implements Fallable {
	use FallableTrait;

	public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo) {
		parent::__construct($idInfo, $name, $typeInfo);
	}
}
