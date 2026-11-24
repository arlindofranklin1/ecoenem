<?php
$eco_nav_atual = basename($_SERVER['PHP_SELF'] ?? '');
$eco_nav_itens = [
    ['arquivo' => 'ecoia.php', 'nome' => 'EcoIA', 'icone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.5 5.5 3.5 9S14.5 18.5 12 21M12 3C9.5 5.5 8.5 8.5 8.5 12S9.5 18.5 12 21M5 7h14M5 17h14"/></svg>'],
    ['arquivo' => 'conteudo.php', 'nome' => 'Conteúdo', 'icone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21zM4 5.5V21M8 7h8M8 11h8"/></svg>'],
    ['arquivo' => 'questoes.php', 'nome' => 'Questões', 'icone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6h11M9 12h11M9 18h11M3 6l1.2 1.2L6.5 5M3 12l1.2 1.2L6.5 11M3 18l1.2 1.2L6.5 17"/></svg>'],
    ['arquivo' => 'simulado.php', 'nome' => 'Simuladão', 'icone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h8M9 3h6v3H9zM6 5H4v16h16V5h-2M8 12l2 2 5-5M8 17h8"/></svg>'],
    ['arquivo' => 'sobre.php', 'nome' => 'Sobre', 'icone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg>'],
];
?>
<nav class="eco-bottom-bar" aria-label="Navegação principal mobile">
    <?php foreach ($eco_nav_itens as $item): ?>
        <a class="eco-bottom-item<?= $eco_nav_atual === $item['arquivo'] ? ' ativo' : '' ?>"
           href="<?= htmlspecialchars($item['arquivo'], ENT_QUOTES, 'UTF-8') ?>"
           <?= $eco_nav_atual === $item['arquivo'] ? 'aria-current="page"' : '' ?>>
            <?= $item['icone'] ?>
            <span><?= htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8') ?></span>
        </a>
    <?php endforeach; ?>
</nav>
