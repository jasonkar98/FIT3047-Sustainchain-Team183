<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
    <h1>Products</h1>

    <?php if ($products->isEmpty()): ?>
        <p>No products found.</p>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <?php foreach ($products as $product): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 16px;">
                    <h3><?= h($product->name) ?></h3>
                    <p style="color: #666;"><?= h($product->category) ?></p>
                    <p style="font-size: 1.3em; font-weight: bold;">$<?= h($product->price) ?></p>
                    <p><?= h(mb_strimwidth($product->description, 0, 80, '...')) ?></p>
                    <a href="/products/<?= $product->id ?>">View Details →</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>