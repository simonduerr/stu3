<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Stu\Orm\Entity\AllianceBoard;
use Stu\Orm\Entity\AllianceBoardPost;
use Stu\Orm\Entity\AllianceBoardTopic;
use Stu\Orm\Entity\AllianceRelation;
use Stu\Orm\Entity\BuildingCost;
use Stu\Orm\Entity\BuildingFieldAlternative;
use Stu\Orm\Entity\BuildingFunction;
use Stu\Orm\Entity\BuildingGood;
use Stu\Orm\Entity\BuildingUpgrade;
use Stu\Orm\Entity\BuildingUpgradeCost;
use Stu\Orm\Entity\BuildplanHangar;
use Stu\Orm\Entity\BuildplanModule;
use Stu\Orm\Entity\ColonyShipQueue;
use Stu\Orm\Entity\ColonyShipRepair;
use Stu\Orm\Entity\ColonyStorage;
use Stu\Orm\Entity\ColonyTerraforming;
use Stu\Orm\Entity\Commodity;
use Stu\Orm\Entity\Crew;
use Stu\Orm\Entity\CrewRace;
use Stu\Orm\Entity\CrewTraining;
use Stu\Orm\Entity\DatabaseCategory;
use Stu\Orm\Entity\DatabaseEntry;
use Stu\Orm\Entity\DatabaseType;
use Stu\Orm\Entity\DatabaseUser;
use Stu\Orm\Entity\Faction;
use Stu\Orm\Entity\GameConfig;
use Stu\Orm\Entity\GameTurn;
use Stu\Orm\Entity\History;
use Stu\Orm\Entity\IgnoreList;
use Stu\Orm\Entity\KnComment;
use Stu\Orm\Entity\KnPost;
use Stu\Orm\Entity\Map;
use Stu\Orm\Entity\MapBorderType;
use Stu\Orm\Entity\MapFieldType;
use Stu\Orm\Entity\MapRegion;
use Stu\Orm\Entity\Module;
use Stu\Orm\Entity\ModuleBuildingFunction;
use Stu\Orm\Entity\ModuleCost;
use Stu\Orm\Entity\ModuleQueue;
use Stu\Orm\Entity\ModuleSpecial;
use Stu\Orm\Entity\News;
use Stu\Orm\Entity\Note;
use Stu\Orm\Entity\PlanetFieldType;
use Stu\Orm\Entity\PlanetFieldTypeBuilding;
use Stu\Orm\Entity\PlanetType;
use Stu\Orm\Entity\Research;
use Stu\Orm\Entity\ResearchDependency;
use Stu\Orm\Entity\Researched;
use Stu\Orm\Entity\RpgPlot;
use Stu\Orm\Entity\RpgPlotMember;
use Stu\Orm\Entity\SessionString;
use Stu\Orm\Entity\ShipBuildplan;
use Stu\Orm\Entity\ShipCrew;
use Stu\Orm\Entity\ShipRump;
use Stu\Orm\Entity\ShipRumpBuildingFunction;
use Stu\Orm\Entity\ShipRumpCategory;
use Stu\Orm\Entity\ShipRumpCategoryRoleCrew;
use Stu\Orm\Entity\ShipRumpColonizationBuilding;
use Stu\Orm\Entity\ShipRumpCost;
use Stu\Orm\Entity\ShipRumpModuleLevel;
use Stu\Orm\Entity\ShipRumpRole;
use Stu\Orm\Entity\ShipRumpSpecial;
use Stu\Orm\Entity\ShipRumpUser;
use Stu\Orm\Entity\ShipStorage;
use Stu\Orm\Entity\ShipSystem;
use Stu\Orm\Entity\StarSystem;
use Stu\Orm\Entity\StarSystemMap;
use Stu\Orm\Entity\StarSystemType;
use Stu\Orm\Entity\Terraforming;
use Stu\Orm\Entity\TerraformingCost;
use Stu\Orm\Entity\TorpedoType;
use Stu\Orm\Entity\TradeLicense;
use Stu\Orm\Entity\TradeOffer;
use Stu\Orm\Entity\TradePost;
use Stu\Orm\Entity\TradeShoutbox;
use Stu\Orm\Entity\TradeStorage;
use Stu\Orm\Entity\TradeTransfer;
use Stu\Orm\Entity\UserIpTable;
use Stu\Orm\Entity\UserProfileVisitor;
use Stu\Orm\Entity\Weapon;

return [
    AllianceBoardRepositoryInterface::class => function (
        ContainerInterface $c
    ): AllianceBoardRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(AllianceBoard::class);
    },
    AllianceBoardPostRepositoryInterface::class => function (
        ContainerInterface $c
    ): AllianceBoardPostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(AllianceBoardPost::class);
    },
    AllianceBoardTopicRepositoryInterface::class => function (
        ContainerInterface $c
    ): AllianceBoardTopicRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(AllianceBoardTopic::class);
    },
    AllianceRelationRepositoryInterface::class => function (
        ContainerInterface $c
    ): AllianceRelationRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(AllianceRelation::class);
    },
    BuildingCostRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildingCostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildingCost::class);
    },
    BuildingFieldAlternativeRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildingFieldAlternativeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildingFieldAlternative::class);
    },
    BuildingFunctionRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildingFunctionRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildingFunction::class);
    },
    BuildingGoodRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildingGoodRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildingGood::class);
    },
    BuildingUpgradeRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildingUpgradeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildingUpgrade::class);
    },
    BuildingUpgradeCostRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildingUpgradeCostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildingUpgradeCost::class);
    },
    BuildplanHangarRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildplanHangarRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildplanHangar::class);
    },
    BuildplanModuleRepositoryInterface::class => function (
        ContainerInterface $c
    ): BuildplanModuleRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(BuildplanModule::class);
    },
    ColonyTerraformingRepositoryInterface::class => function (
        ContainerInterface $c
    ): ColonyTerraformingRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ColonyTerraforming::class);
    },
    CommodityRepositoryInterface::class => function (
        ContainerInterface $c
    ): CommodityRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Commodity::class);
    },
    ColonyShipRepairRepositoryInterface::class => function (
        ContainerInterface $c
    ): ColonyShipRepairRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ColonyShipRepair::class);
    },
    ColonyShipQueueRepositoryInterface::class => function (
        ContainerInterface $c
    ): ColonyShipQueueRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ColonyShipQueue::class);
    },
    ColonyStorageRepositoryInterface::class => function (
        ContainerInterface $c
    ): ColonyStorageRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ColonyStorage::class);
    },
    CrewRaceRepositoryInterface::class => function (
        ContainerInterface $c
    ): CrewRaceRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(CrewRace::class);
    },
    CrewRepositoryInterface::class => function (
        ContainerInterface $c
    ): CrewRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Crew::class);
    },
    CrewTrainingRepositoryInterface::class => function (
        ContainerInterface $c
    ): CrewTrainingRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(CrewTraining::class);
    },
    DatabaseCategoryRepositoryInterface::class => function (
        ContainerInterface $c
    ): DatabaseCategoryRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(DatabaseCategory::class);
    },
    DatabaseEntryRepositoryInterface::class => function (
        ContainerInterface $c
    ): DatabaseEntryRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(DatabaseEntry::class);
    },
    DatabaseTypeRepositoryInterface::class => function (
        ContainerInterface $c
    ): DatabaseTypeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(DatabaseType::class);
    },
    DatabaseUserRepositoryInterface::class => function (
        ContainerInterface $c
    ): DatabaseUserRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(DatabaseUser::class);
    },
    FactionRepositoryInterface::class => function (
        ContainerInterface $c
    ): FactionRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Faction::class);
    },
    GameConfigRepositoryInterface::class => function (
        ContainerInterface $c
    ): GameConfigRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(GameConfig::class);
    },
    GameTurnRepositoryInterface::class => function (
        ContainerInterface $c
    ): GameTurnRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(GameTurn::class);
    },
    HistoryRepositoryInterface::class => function (
        ContainerInterface $c
    ): HistoryRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(History::class);
    },
    IgnoreListRepositoryInterface::class => function (
        ContainerInterface $c
    ): IgnoreListRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(IgnoreList::class);
    },
    KnCommentRepositoryInterface::class => function (
        ContainerInterface $c
    ): KnCommentRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(KnComment::class);
    },
    KnPostRepositoryInterface::class => function (
        ContainerInterface $c
    ): KnPostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(KnPost::class);
    },
    MapBorderTypeRepositoryInterface::class => function (
        ContainerInterface $c
    ): MapBorderTypeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(MapBorderType::class);
    },
    MapFieldTypeRepositoryInterface::class => function (
        ContainerInterface $c
    ): MapFieldTypeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(MapFieldType::class);
    },
    MapRegionRepositoryInterface::class => function (
        ContainerInterface $c
    ): MapRegionRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(MapRegion::class);
    },
    MapRepositoryInterface::class => function (
        ContainerInterface $c
    ): MapRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Map::class);
    },
    ModuleBuildingFunctionRepositoryInterface::class => function (
        ContainerInterface $c
    ): ModuleBuildingFunctionRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ModuleBuildingFunction::class);
    },
    ModuleCostRepositoryInterface::class => function (
        ContainerInterface $c
    ): ModuleCostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ModuleCost::class);
    },
    ModuleRepositoryInterface::class => function (
        ContainerInterface $c
    ): ModuleRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Module::class);
    },
    ModuleQueueRepositoryInterface::class => function (
        ContainerInterface $c
    ): ModuleQueueRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ModuleQueue::class);
    },
    ModuleSpecialRepositoryInterface::class => function (
        ContainerInterface $c
    ): ModuleSpecialRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ModuleSpecial::class);
    },
    NewsRepositoryInterface::class => function (
        ContainerInterface $c
    ): NewsRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(News::class);
    },
    NoteRepositoryInterface::class => function (
        ContainerInterface $c
    ): NoteRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Note::class);
    },
    PlanetFieldTypeBuildingRepositoryInterface::class => function (
        ContainerInterface $c
    ): PlanetFieldTypeBuildingRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(PlanetFieldTypeBuilding::class);
    },
    PlanetFieldTypeRepositoryInterface::class => function (
        ContainerInterface $c
    ): PlanetFieldTypeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(PlanetFieldType::class);
    },
    PlanetTypeRepositoryInterface::class => function (
        ContainerInterface $c
    ): PlanetTypeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(PlanetType::class);
    },
    ResearchRepositoryInterface::class => function (
        ContainerInterface $c
    ): ResearchRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Research::class);
    },
    ResearchedRepositoryInterface::class => function (
        ContainerInterface $c
    ): ResearchedRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Researched::class);
    },
    ResearchDependencyRepositoryInterface::class => function (
        ContainerInterface $c
    ): ResearchDependencyRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ResearchDependency::class);
    },
    RpgPlotRepositoryInterface::class => function (
        ContainerInterface $c
    ): RpgPlotRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(RpgPlot::class);
    },
    RpgPlotMemberRepositoryInterface::class => function (
        ContainerInterface $c
    ): RpgPlotMemberRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(RpgPlotMember::class);
    },
    SessionStringRepositoryInterface::class => function (
        ContainerInterface $c
    ): SessionStringRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(SessionString::class);
    },
    ShipBuildplanRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipBuildplanRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipBuildplan::class);
    },
    ShipCrewRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipCrewRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipCrew::class);
    },
    ShipRumpBuildingFunctionRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpBuildingFunctionRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpBuildingFunction::class);
    },
    ShipRumpCategoryRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpCategoryRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpCategory::class);
    },
    ShipRumpCategoryRoleCrewRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpCategoryRoleCrewRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpCategoryRoleCrew::class);
    },
    ShipRumpColonizationBuildingRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpColonizationBuildingRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpColonizationBuilding::class);
    },
    ShipRumpCostRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpCostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpCost::class);
    },
    ShipRumpModuleLevelRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpModuleLevelRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpModuleLevel::class);
    },
    ShipRumpRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRump::class);
    },
    ShipRumpRoleRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpRoleRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpRole::class);
    },
    ShipRumpSpecialRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpSpecialRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpSpecial::class);
    },
    ShipRumpUserRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipRumpUserRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipRumpUser::class);
    },
    ShipStorageRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipStorageRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipStorage::class);
    },
    ShipSystemRepositoryInterface::class => function (
        ContainerInterface $c
    ): ShipSystemRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(ShipSystem::class);
    },
    StarSystemMapRepositoryInterface::class => function (
        ContainerInterface $c
    ): StarSystemMapRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(StarSystemMap::class);
    },
    StarSystemRepositoryInterface::class => function (
        ContainerInterface $c
    ): StarSystemRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(StarSystem::class);
    },
    StarSystemTypeRepositoryInterface::class => function (
        ContainerInterface $c
    ): StarSystemTypeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(StarSystemType::class);
    },
    TerraformingRepositoryInterface::class => function (
        ContainerInterface $c
    ): TerraformingRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Terraforming::class);
    },
    TerraformingCostRepositoryInterface::class => function (
        ContainerInterface $c
    ): TerraformingCostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TerraformingCost::class);
    },
    TorpedoTypeRepositoryInterface::class => function (
        ContainerInterface $c
    ): TorpedoTypeRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TorpedoType::class);
    },
    TradeLicenseRepositoryInterface::class => function (
        ContainerInterface $c
    ): TradeLicenseRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TradeLicense::class);
    },
    TradeOfferRepositoryInterface::class => function (
        ContainerInterface $c
    ): TradeOfferRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TradeOffer::class);
    },
    TradePostRepositoryInterface::class => function (
        ContainerInterface $c
    ): TradePostRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TradePost::class);
    },
    TradeShoutboxRepositoryInterface::class => function (
        ContainerInterface $c
    ): TradeShoutboxRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TradeShoutbox::class);
    },
    TradeStorageRepositoryInterface::class => function (
        ContainerInterface $c
    ): TradeStorageRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TradeStorage::class);
    },
    TradeTransferRepositoryInterface::class => function (
        ContainerInterface $c
    ): TradeTransferRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(TradeTransfer::class);
    },
    UserIpTableRepositoryInterface::class => function (
        ContainerInterface $c
    ): UserIpTableRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(UserIpTable::class);
    },
    UserProfileVisitorRepositoryInterface::class => function (
        ContainerInterface $c
    ): UserProfileVisitorRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(UserProfileVisitor::class);
    },
    WeaponRepositoryInterface::class => function (
        ContainerInterface $c
    ): WeaponRepositoryInterface {
        return $c->get(EntityManagerInterface::class)->getRepository(Weapon::class);
    },
];