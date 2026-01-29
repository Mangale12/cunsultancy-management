import React, { useState, useCallback } from 'react';
import { Upload, X, FileText, AlertCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Badge } from '@/components/ui/badge';
import InputError from '@/components/input-error';

interface FileItem {
    id: string;
    file: File;
    name: string;
    size: number;
    type: string;
    description?: string;
    isPrimary?: boolean;
    sortOrder: number;
}

interface MultiFileUploadProps {
    onFilesChange: (files: FileItem[]) => void;
    selectedFiles: FileItem[];
    error?: string;
    accept?: string;
    maxSize?: number;
    maxFiles?: number;
    allowMultiple?: boolean;
}

export default function MultiFileUpload({
    onFilesChange,
    selectedFiles,
    error,
    accept = '.pdf,.doc,.docx,.jpg,.jpeg,.png',
    maxSize = 10240, // 10MB in KB
    maxFiles = 1,
    allowMultiple = false,
}: MultiFileUploadProps) {
    const [isDragOver, setIsDragOver] = useState(false);
    const [uploadProgress, setUploadProgress] = useState<{ [key: string]: number }>({});

    const formatFileSize = (bytes: number) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    const getFileIcon = (fileType: string) => {
        const extension = fileType.toLowerCase();
        if (['pdf'].includes(extension)) return '📄';
        if (['doc', 'docx'].includes(extension)) return '📝';
        if (['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(extension)) return '🖼️';
        if (['xls', 'xlsx'].includes(extension)) return '📊';
        if (['zip', 'rar', '7z'].includes(extension)) return '📦';
        return '📎';
    };

    const validateFile = (file: File): string | null => {
        // Check file size
        if (file.size > maxSize * 1024) {
            return `File size exceeds maximum allowed size of ${Math.round(maxSize / 1024)}MB`;
        }

        // Check file type
        const allowedExtensions = accept.split(',').map(ext => ext.trim().replace('.', ''));
        const fileExtension = file.name.split('.').pop()?.toLowerCase();
        
        if (!fileExtension || !allowedExtensions.includes(fileExtension)) {
            return `File type not allowed. Allowed types: ${allowedExtensions.join(', ')}`;
        }

        return null;
    };

    const handleFileSelect = useCallback((files: FileList | null) => {
        if (!files) return;

        const newFiles: FileItem[] = [];
        const errors: string[] = [];

        Array.from(files).forEach((file, index) => {
            // Check if we've reached the max file limit
            if (selectedFiles.length + newFiles.length > maxFiles) {
                errors.push(`Maximum ${maxFiles} files allowed`);
                return;
            }

            // Validate file
            const validationError = validateFile(file);
            if (validationError) {
                errors.push(`${file.name}: ${validationError}`);
                return;
            }

            // Check for duplicates
            const isDuplicate = selectedFiles.some(
                selectedFile => selectedFile.file.name === file.name && selectedFile.file.size === file.size
            );

            if (!isDuplicate) {
                newFiles.push({
                    id: `${Date.now()}-${index}`,
                    file,
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    isPrimary: selectedFiles.length === 0 && index === 0, // First file is primary
                    sortOrder: selectedFiles.length + index,
                });
            }
        });

        if (errors.length > 0) {
            console.error('File validation errors:', errors);
        }

        if (newFiles.length > 0) {
            onFilesChange([...selectedFiles, ...newFiles]);
        }
    }, [selectedFiles, maxFiles, maxSize, accept, onFilesChange]);

    const handleDrop = useCallback((e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        setIsDragOver(false);
        handleFileSelect(e.dataTransfer.files);
    }, [handleFileSelect]);

    const handleDragOver = useCallback((e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        setIsDragOver(true);
    }, []);

    const handleDragLeave = useCallback((e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        setIsDragOver(false);
    }, []);

    const handleFileInput = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
        handleFileSelect(e.target.files);
    }, [handleFileSelect]);

    const removeFile = useCallback((fileId: string) => {
        const updatedFiles = selectedFiles.filter(file => file.id !== fileId);
        
        // If we removed the primary file, make the first remaining file primary
        if (selectedFiles.find(f => f.id === fileId)?.isPrimary && updatedFiles.length > 0) {
            updatedFiles[0].isPrimary = true;
        }
        
        onFilesChange(updatedFiles);
    }, [selectedFiles, onFilesChange]);

    const setPrimaryFile = useCallback((fileId: string) => {
        const updatedFiles = selectedFiles.map(file => ({
            ...file,
            isPrimary: file.id === fileId
        }));
        onFilesChange(updatedFiles);
    }, [selectedFiles, onFilesChange]);

    const updateFileDescription = useCallback((fileId: string, description: string) => {
        const updatedFiles = selectedFiles.map(file => 
            file.id === fileId ? { ...file, description } : file
        );
        onFilesChange(updatedFiles);
    }, [selectedFiles, onFilesChange]);

    const reorderFiles = useCallback((fromIndex: number, toIndex: number) => {
        const updatedFiles = [...selectedFiles];
        const [movedFile] = updatedFiles.splice(fromIndex, 1);
        updatedFiles.splice(toIndex, 0, movedFile);
        
        // Update sort orders
        updatedFiles.forEach((file, index) => {
            file.sortOrder = index;
        });
        
        onFilesChange(updatedFiles);
    }, [selectedFiles, onFilesChange]);

    return (
        <div className="space-y-4">
            {/* Upload Area */}
            <Card 
                className={`border-2 border-dashed transition-colors ${
                    isDragOver ? 'border-blue-400 bg-blue-50' : 'border-gray-300'
                }`}
            >
                <CardContent className="p-6">
                    <div
                        onDrop={handleDrop}
                        onDragOver={handleDragOver}
                        onDragLeave={handleDragLeave}
                        className="text-center"
                    >
                        <Upload className="mx-auto h-12 w-12 text-gray-400" />
                        <div className="mt-2">
                            <p className="text-lg font-medium">
                                {allowMultiple ? 'Drop files here or click to upload' : 'Drop file here or click to upload'}
                            </p>
                            <p className="text-sm text-gray-500 mt-1">
                                {allowMultiple ? `Up to ${maxFiles} files` : 'Single file'} • Max {Math.round(maxSize / 1024)}MB each
                            </p>
                            <p className="text-xs text-gray-400 mt-1">
                                Supported: {accept.split(',').join(', ')}
                            </p>
                        </div>
                        <div className="mt-4">
                            <Button variant="outline" asChild>
                                <label className="cursor-pointer">
                                    <input
                                        type="file"
                                        className="hidden"
                                        onChange={handleFileInput}
                                        accept={accept}
                                        multiple={allowMultiple}
                                    />
                                    Select Files
                                </label>
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* Error Display */}
            {error && (
                <div className="flex items-center gap-2 text-red-600 bg-red-50 p-3 rounded-lg">
                    <AlertCircle className="h-4 w-4" />
                    <span className="text-sm">{error}</span>
                </div>
            )}

            {/* File List */}
            {selectedFiles.length > 0 && (
                <Card>
                    <CardContent className="p-4">
                        <h4 className="font-medium mb-3">
                            {selectedFiles.length} file{selectedFiles.length > 1 ? 's' : ''} selected
                        </h4>
                        <div className="space-y-3">
                            {selectedFiles.map((file, index) => (
                                <div key={file.id} className="flex items-center gap-3 p-3 border rounded-lg">
                                    <div className="text-2xl">{getFileIcon(file.type)}</div>
                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-center gap-2">
                                            <p className="font-medium truncate">{file.name}</p>
                                            {file.isPrimary && (
                                                <Badge variant="default" className="text-xs">Primary</Badge>
                                            )}
                                        </div>
                                        <p className="text-sm text-gray-500">{formatFileSize(file.size)}</p>
                                        {uploadProgress[file.id] !== undefined && (
                                            <Progress value={uploadProgress[file.id]} className="mt-2" />
                                        )}
                                    </div>
                                    <div className="flex items-center gap-2">
                                        {allowMultiple && selectedFiles.length > 1 && (
                                            <Button
                                                size="sm"
                                                variant={file.isPrimary ? "default" : "outline"}
                                                onClick={() => setPrimaryFile(file.id)}
                                            >
                                                {file.isPrimary ? 'Primary' : 'Set Primary'}
                                            </Button>
                                        )}
                                        <Button
                                            size="sm"
                                            variant="destructive"
                                            onClick={() => removeFile(file.id)}
                                        >
                                            <X className="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>
            )}
        </div>
    );
}
