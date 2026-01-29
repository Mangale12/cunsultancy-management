import React, { useState, useRef } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Upload, File, X, AlertCircle, CheckCircle } from 'lucide-react';
import InputError from '@/components/input-error';

interface DocumentUploadProps {
    onFileSelect: (file: File) => void;
    onFileRemove: () => void;
    selectedFile: File | null;
    error?: string;
    accept?: string;
    maxSize?: number; // in MB
    className?: string;
}

export default function DocumentUpload({
    onFileSelect,
    onFileRemove,
    selectedFile,
    error,
    accept = '.pdf,.jpg,.jpeg,.png,.doc,.docx',
    maxSize = 10,
    className = '',
}: DocumentUploadProps) {
    const [dragActive, setDragActive] = useState(false);
    const [uploadProgress, setUploadProgress] = useState(0);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const handleDrag = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
        if (e.type === 'dragenter' || e.type === 'dragover') {
            setDragActive(true);
        } else if (e.type === 'dragleave') {
            setDragActive(false);
        }
    };

    const handleDrop = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(false);

        const files = e.dataTransfer.files;
        if (files && files[0]) {
            handleFile(files[0]);
        }
    };

    const handleFileInput = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = e.target.files;
        if (files && files[0]) {
            handleFile(files[0]);
        }
    };

    const handleFile = (file: File) => {
        // Validate file size
        if (file.size > maxSize * 1024 * 1024) {
            return;
        }

        // Validate file type
        const fileExtension = '.' + file.name.split('.').pop()?.toLowerCase();
        const allowedTypes = accept.split(',').map(type => type.trim());
        
        if (!allowedTypes.includes(fileExtension) && !allowedTypes.includes('*')) {
            return;
        }

        onFileSelect(file);
        setUploadProgress(100);
    };

    const openFileDialog = () => {
        fileInputRef.current?.click();
    };

    const formatFileSize = (bytes: number) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    const getFileIcon = (fileName: string) => {
        const extension = fileName.split('.').pop()?.toLowerCase();
        switch (extension) {
            case 'pdf':
                return '📄';
            case 'doc':
            case 'docx':
                return '📝';
            case 'jpg':
            case 'jpeg':
            case 'png':
                return '🖼️';
            default:
                return '📎';
        }
    };

    return (
        <div className={`space-y-4 ${className}`}>
            <div
                className={`relative border-2 border-dashed rounded-lg p-6 transition-colors ${
                    dragActive
                        ? 'border-blue-400 bg-blue-50'
                        : 'border-gray-300 hover:border-gray-400'
                } ${selectedFile ? 'border-green-400 bg-green-50' : ''}`}
                onDragEnter={handleDrag}
                onDragLeave={handleDrag}
                onDragOver={handleDrag}
                onDrop={handleDrop}
            >
                <input
                    ref={fileInputRef}
                    type="file"
                    accept={accept}
                    onChange={handleFileInput}
                    className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    disabled={!!selectedFile}
                />

                {!selectedFile ? (
                    <div className="text-center">
                        <Upload className="mx-auto h-12 w-12 text-gray-400" />
                        <div className="mt-4">
                            <p className="text-lg font-medium text-gray-900">
                                Drop your file here, or{' '}
                                <Button
                                    type="button"
                                    variant="link"
                                    onClick={openFileDialog}
                                    className="p-0 h-auto font-medium text-blue-600 hover:text-blue-500"
                                >
                                    browse
                                </Button>
                            </p>
                            <p className="text-sm text-gray-500 mt-1">
                                Supported formats: {accept.replace(/\./g, '').toUpperCase()}
                            </p>
                            <p className="text-sm text-gray-500">
                                Maximum file size: {maxSize}MB
                            </p>
                        </div>
                    </div>
                ) : (
                    <div className="text-center">
                        <div className="flex items-center justify-center space-x-3">
                            <span className="text-4xl">{getFileIcon(selectedFile.name)}</span>
                            <div className="text-left">
                                <p className="font-medium text-gray-900">{selectedFile.name}</p>
                                <p className="text-sm text-gray-500">{formatFileSize(selectedFile.size)}</p>
                            </div>
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                onClick={onFileRemove}
                                className="ml-auto"
                            >
                                <X className="h-4 w-4" />
                            </Button>
                        </div>
                        {uploadProgress > 0 && (
                            <div className="mt-4">
                                <Progress value={uploadProgress} className="h-2" />
                            </div>
                        )}
                    </div>
                )}
            </div>

            {error && (
                <Alert variant="destructive">
                    <AlertCircle className="h-4 w-4" />
                    <AlertDescription>{error}</AlertDescription>
                </Alert>
            )}

            {selectedFile && !error && (
                <Alert>
                    <CheckCircle className="h-4 w-4" />
                    <AlertDescription>
                        File "{selectedFile.name}" is ready for upload.
                    </AlertDescription>
                </Alert>
            )}

            <InputError message={error} />
        </div>
    );
}
