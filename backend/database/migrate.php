<?php
require_once 'Database.php';
require_once 'Migration.php';
require_once 'migrations/CreateDisciplinasTable.php';
require_once 'migrations/CreateAlunosTable.php';

// Registrar todas as migrations na ordem correta
Migration::register(new CreateDisciplinasTable()); // Primeiro disciplinas
Migration::register(new CreateAlunosTable());      // Depois alunos e relacionamentos

// Executar migrations
try {
    $migration = new Migration();
    
    // Verificar comando
    $command = $argv[1] ?? 'migrate';
    
    switch ($command) {
        case 'migrate':
            $executed = $migration->migrate();
            if (empty($executed)) {
                echo "Nenhuma nova migration para executar.\n";
            } else {
                echo "Migrations executadas com sucesso:\n";
                foreach ($executed as $migration) {
                    echo "- {$migration}\n";
                }
            }
            break;
            
        case 'rollback':
            $steps = isset($argv[2]) ? (int)$argv[2] : 1;
            $rolledBack = $migration->rollback($steps);
            if (empty($rolledBack)) {
                echo "Nenhuma migration para reverter.\n";
            } else {
                echo "Migrations revertidas com sucesso:\n";
                foreach ($rolledBack as $migration) {
                    echo "- {$migration}\n";
                }
            }
            break;
            
        case 'reset':
            $rolledBack = $migration->reset();
            if (empty($rolledBack)) {
                echo "Nenhuma migration para resetar.\n";
            } else {
                echo "Todas as migrations foram revertidas com sucesso:\n";
                foreach ($rolledBack as $migration) {
                    echo "- {$migration}\n";
                }
            }
            break;
            
        case 'refresh':
            echo "Revertendo todas as migrations...\n";
            $migration->reset();
            echo "Executando migrations novamente...\n";
            $executed = $migration->migrate();
            echo "Refresh completo!\n";
            break;
            
        default:
            echo "Comando inválido. Use: migrate, rollback, reset ou refresh\n";
            break;
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
} 