<?php

declare(strict_types=1);

namespace Lyrica0954\JapaneseBlock;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\block\Material;
use customiesdevs\customies\block\Model;
use customiesdevs\customies\item\CreativeInventoryInfo;
use Lyrica0954\JapaneseBlock\block\JapaneseBlock;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\Filesystem\Path;

class Main extends PluginBase implements Listener {

	public function onJoin(PlayerJoinEvent $event): void {
		$player = $event->getPlayer();

		$blocks = (new ReflectionClass(CustomiesBlockFactory::class))->getProperty("customBlocks")->getValue(CustomiesBlockFactory::getInstance());

		foreach ($blocks as $block) {
			$player->getCreativeInventory()->add($block->asItem());
		}

		$player->getNetworkSession()->getInvManager()->syncCreative();
	}

	protected function onEnable(): void {
		$this->registerBlocks();

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	protected function registerBlocks(): void {
		foreach ($this->getEntries() as $data) {
			$char = $data["char"];
			$identity = $data["identity"];
			$material = new Material(Material::TARGET_ALL, $identity, Material::RENDER_METHOD_ALPHA_TEST);
			$model = new Model([$material]);

			$creativeInfo = new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_CONSTRUCTION);
			CustomiesBlockFactory::getInstance()->registerBlock(
				static fn() => new JapaneseBlock(
					new BlockIdentifier(BlockTypeIds::newId()),
					$char,
					self::getJapaneseBlockTypeInfo()
				),
				"japanese_block:" . $identity,
				$model,
				$creativeInfo
			);

			$this->getLogger()->info("Registered $char, identity: $identity");
		}
	}

	/**
	 * @return (array{
	 *     char: string,
	 *     identity: string
	 * })[]
	 */
	protected function getEntries(): array {
		$path = Path::join($this->getDataFolder(), "entries.json");

		if (!file_exists($path)) {
			throw new RuntimeException("Not found entries.json");
		}

		return json_decode(file_get_contents($path), true);
	}

	protected static function getJapaneseBlockTypeInfo(): BlockTypeInfo {
		return new BlockTypeInfo(new BlockBreakInfo(0.6));
	}
}
