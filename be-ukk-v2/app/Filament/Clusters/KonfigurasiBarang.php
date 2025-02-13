<?php

namespace App\Filament\Clusters;

use App\Filament\NavigationGroups;
use Filament\Clusters\Cluster;

class KonfigurasiBarang extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string $name = 'Konfigurasi Barang';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationGroup = NavigationGroups::RENT_MANAGEMENT;
    protected static ?string $navigationLabel = 'Konfigurasi Barang';

}