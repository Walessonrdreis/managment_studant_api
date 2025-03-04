<?php

function showMainMenu() {
    echo "Escolha uma categoria:\n";
    echo "1. Gerenciar Serviços\n";
    echo "2. Gerenciar Controladores\n";
    echo "3. Gerenciar Modelos\n";
    echo "4. Gerenciar Banco de Dados\n";
    echo "5. Gerenciar Providers\n";
    echo "0. Sair\n";
}

function showServiceMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Criar Serviço\n";
    echo "0. Voltar ao Menu Principal\n";
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

function showDatabaseMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Testar Conexão com o Banco de Dados\n";
    echo "0. Voltar ao Menu Principal\n";
}

function showProviderMenu() {
    echo "Escolha uma opção:\n";
    echo "1. Criar ServiceProvider\n";
    echo "0. Voltar ao Menu Principal\n";
}

function handleMainChoice($choice) {
    switch ($choice) {
        case 1:
            handleServiceMenu();
            break;
        case 2:
            handleControllerMenu();
            break;
        case 3:
            handleModelMenu();
            break;
        case 4:
            handleDatabaseMenu();
            break;
        case 5:
            handleProviderMenu();
            break;
        case 0:
            echo "Saindo...\n";
            exit;
        default:
            echo "Opção inválida. Tente novamente.\n";
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

function handleDatabaseMenu() {
    while (true) {
        showDatabaseMenu();
        $choice = (int)readline("Digite sua escolha: ");
        if ($choice === 1) {
            include 'database/db_connection.php'; // Script para testar conexão com o banco de dados
        } elseif ($choice === 0) {
            return; // Volta ao menu principal
        } else {
            echo "Opção inválida. Tente novamente.\n";
        }
    }
}

function handleProviderMenu() {
    while (true) {
        showProviderMenu();
        $choice = (int)readline("Digite sua escolha: ");
        if ($choice === 1) {
            include 'providers/make_provider.php'; // Script para criar ServiceProvider
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
