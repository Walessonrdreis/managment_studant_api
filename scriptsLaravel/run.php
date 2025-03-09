#!/usr/bin/env php
<?php

function showMainMenu() {
    echo "Escolha uma categoria:\n";
    echo "1. Gerenciar Controladores\n";
    echo "2. Gerenciar Modelos\n";
    echo "3. Gerenciar Serviços\n";
    echo "4. Gerenciar Migrations\n";
    echo "5. Gerenciar Seeders\n";
    echo "0. Sair\n";
}

function showControllerMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Criar Controlador\n";
    echo "0. Voltar ao Menu Principal\n";
}

function showModelMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Criar Modelo\n";
    echo "0. Voltar ao Menu Principal\n";
}

function showServiceMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Criar Serviço\n";
    echo "0. Voltar ao Menu Principal\n";
}

function showMigrationMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Criar Migração\n";
    echo "0. Voltar ao Menu Principal\n";
}

function showSeederMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Criar Seeder\n";
    echo "0. Voltar ao Menu Principal\n";
}

function handleMainChoice($choice) {
    switch ($choice) {
        case 1:
            handleControllerMenu();
            break;
        case 2:
            handleModelMenu();
            break;
        case 3:
            handleServiceMenu();
            break;
        case 4:
            handleMigrationMenu();
            break;
        case 5:
            handleSeederMenu();
            break;
        case 0:
            echo "Saindo...\n";
            exit;
        default:
            echo "Opção inválida. Tente novamente.\n";
    }
}

function handleControllerMenu() {
    while (true) {
        showControllerMenu();
        $choice = (int)readline("Digite sua escolha: ");
        if ($choice === 1) {
            include 'controllers/make_controller.php'; // Script para criar controlador
        } elseif ($choice === 0) {
            return; // Volta ao menu principal
        } else {
            echo "Opção inválida. Tente novamente.\n";
        }
    }
}

function handleModelMenu() {
    while (true) {
        showModelMenu();
        $choice = (int)readline("Digite sua escolha: ");
        if ($choice === 1) {
            include 'models/make_model.php'; // Script para criar modelo
        } elseif ($choice === 0) {
            return; // Volta ao menu principal
        } else {
            echo "Opção inválida. Tente novamente.\n";
        }
    }
}

function handleServiceMenu() {
    while (true) {
        showServiceMenu();
        $choice = (int)readline("Digite sua escolha: ");
        if ($choice === 1) {
            include 'services/make_service.php'; // Script para criar serviço
        } elseif ($choice === 0) {
            return; // Volta ao menu principal
        } else {
            echo "Opção inválida. Tente novamente.\n";
        }
    }
}

function handleMigrationMenu() {
    while (true) {
        showMigrationMenu();
        $choice = (int)readline("Digite sua escolha: ");
        if ($choice === 1) {
            // Aqui você pode incluir o script para criar migração
            echo "Criar Migração (implementação a ser feita)\n";
        } elseif ($choice === 0) {
            return; // Volta ao menu principal
        } else {
            echo "Opção inválida. Tente novamente.\n";
        }
    }
}

function handleSeederMenu() {
    while (true) {
        showSeederMenu();
        $choice = (int)readline("Digite sua escolha: ");
        if ($choice === 1) {
            // Aqui você pode incluir o script para criar seeder
            echo "Criar Seeder (implementação a ser feita)\n";
        } elseif ($choice === 0) {
            return; // Volta ao menu principal
        } else {
            echo "Opção inválida. Tente novamente.\n";
        }
    }
}

while (true) {
    showMainMenu();
    $choice = (int)readline("Digite sua escolha: ");
    handleMainChoice($choice);
}
