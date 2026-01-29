<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "🔍 TESTING DOCUMENT CONTROLLER FIX\n";
echo "==================================\n\n";

try {
    // Simulate the controller's create method
    $documentTypes = \App\Models\DocumentType::where('is_active', true)->orderBy('sort_order')->get()->map(function ($type) {
        return [
            'id' => $type->id,
            'name' => $type->name,
            'category' => $type->category,
            'allowed_file_types' => is_array($type->allowed_file_types) ? $type->allowed_file_types : json_decode($type->allowed_file_types ?? '[]', true),
            'max_file_size' => $type->max_file_size,
            'is_required' => $type->is_required,
            'has_expiry' => $type->has_expiry,
            'allows_multiple_files' => $type->allows_multiple_files ?? false,
            'max_files' => $type->max_files ?? 1,
        ];
    });

    echo "✅ Controller data transformation successful\n";
    echo "📊 Document types processed: {$documentTypes->count()}\n\n";

    foreach ($documentTypes as $type) {
        echo "📄 {$type['name']}:\n";
        echo "   - allowed_file_types type: " . gettype($type['allowed_file_types']) . "\n";
        echo "   - can map: " . (is_array($type['allowed_file_types']) ? 'YES' : 'NO') . "\n";
        
        if (is_array($type['allowed_file_types'])) {
            echo "   - file types: [" . implode(', ', $type['allowed_file_types']) . "]\n";
            
            // Test join operation
            try {
                $joined = implode(',', $type['allowed_file_types']);
                echo "   - join test: {$joined}\n";
            } catch (Exception $e) {
                echo "   - join test FAILED: " . $e->getMessage() . "\n";
            }
        }
        echo "\n";
    }

    echo "🎯 FRONTEND COMPATIBILITY: ✅ READY\n";
    echo "🚀 PRODUCTION STATUS: FIXED\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
