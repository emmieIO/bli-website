import { EditorContent, useEditor } from '@tiptap/react';
import Link from '@tiptap/extension-link';
import StarterKit from '@tiptap/starter-kit';
import { type ReactNode, useEffect, useMemo, useState } from 'react';
import {
    Bold,
    Code,
    Eye,
    Heading1,
    Heading2,
    Heading3,
    Italic,
    Link2,
    List,
    ListOrdered,
    PanelRight,
    Outdent,
    Pilcrow,
    Quote,
    Redo2,
    RemoveFormatting,
    SeparatorHorizontal,
    Strikethrough,
    Unlink2,
    Undo2,
} from 'lucide-react';
import './RichTextEditor.css';

interface RichTextEditorProps {
    value: string;
    onChange: (value: string) => void;
    label?: string;
    error?: string;
    required?: boolean;
    placeholder?: string;
}

interface ToolbarButtonProps {
    active?: boolean;
    disabled?: boolean;
    title: string;
    onClick: () => void;
    children: ReactNode;
}

function ToolbarButton({ active = false, disabled = false, title, onClick, children }: ToolbarButtonProps) {
    return (
        <button
            type="button"
            onClick={onClick}
            disabled={disabled}
            title={title}
            className={`editor-toolbar-button ${active ? 'editor-toolbar-button-active' : ''}`}
        >
            {children}
        </button>
    );
}

export default function RichTextEditor({
    value,
    onChange,
    label,
    error,
    required,
    placeholder = 'Start typing...',
}: RichTextEditorProps) {
    const [viewMode, setViewMode] = useState<'write' | 'split' | 'preview'>('write');
    const [showLinkPanel, setShowLinkPanel] = useState(false);
    const [linkValue, setLinkValue] = useState('');
    const normalizeUrl = (input: string) => {
        const trimmed = input.trim();

        if (!trimmed) {
            return '';
        }

        if (/^(https?:\/\/|mailto:|tel:)/i.test(trimmed)) {
            return trimmed;
        }

        return `https://${trimmed}`;
    };

    const editor = useEditor({
        extensions: [
            StarterKit.configure({
                heading: {
                    levels: [1, 2, 3],
                },
            }),
            Link.configure({
                openOnClick: true,
                autolink: true,
                defaultProtocol: 'https',
                protocols: ['http', 'https', 'mailto', 'tel'],
            }),
        ],
        content: value,
        editorProps: {
            attributes: {
                class: 'editor-content',
            },
        },
        onUpdate: ({ editor: currentEditor }) => {
            onChange(currentEditor.getHTML());
        },
    });

    useEffect(() => {
        if (editor && value !== editor.getHTML()) {
            editor.commands.setContent(value);
        }
    }, [value, editor]);

    const openLinkPanel = () => {
        if (!editor) {
            return;
        }

        setLinkValue(editor.getAttributes('link').href ?? '');
        setShowLinkPanel(true);
    };

    const applyLink = () => {
        if (!editor) {
            return;
        }

        const url = normalizeUrl(linkValue);

        if (!url) {
            editor.chain().focus().extendMarkRange('link').unsetLink().run();
            setShowLinkPanel(false);
            setLinkValue('');
            return;
        }

        editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
        setShowLinkPanel(false);
    };

    const removeLink = () => {
        if (!editor) {
            return;
        }

        editor.chain().focus().extendMarkRange('link').unsetLink().run();
        setLinkValue('');
        setShowLinkPanel(false);
    };

    const editorStats = useMemo(() => {
        if (!editor) {
            return { words: 0, characters: 0, isEmpty: true };
        }

        const text = editor.getText().replace(/\s+/g, ' ').trim();

        return {
            words: text ? text.split(' ').length : 0,
            characters: text.length,
            isEmpty: text.length === 0,
        };
    }, [editor, value]);

    if (!editor) {
        return null;
    }

    return (
        <div className="relative">
            {label && (
                <div className="mb-2 flex flex-wrap items-center justify-between gap-2">
                    <label className="block text-sm font-semibold text-slate-800">
                        {label}
                        {required && <span className="ml-1 text-red-500">*</span>}
                    </label>
                    <span className="text-xs font-medium text-slate-500">
                        {editorStats.words} words • {editorStats.characters} characters
                    </span>
                </div>
            )}

            <div className={`editor-shell ${error ? 'editor-shell-error' : ''}`}>
                <div className="editor-shell-header">
                    <div className="editor-view-switcher">
                        <button
                            type="button"
                            onClick={() => setViewMode('write')}
                            className={`editor-view-button ${viewMode === 'write' ? 'editor-view-button-active' : ''}`}
                        >
                            Write
                        </button>
                        <button
                            type="button"
                            onClick={() => setViewMode('split')}
                            className={`editor-view-button ${viewMode === 'split' ? 'editor-view-button-active' : ''}`}
                        >
                            <PanelRight size={14} />
                            Split
                        </button>
                        <button
                            type="button"
                            onClick={() => setViewMode('preview')}
                            className={`editor-view-button ${viewMode === 'preview' ? 'editor-view-button-active' : ''}`}
                        >
                            <Eye size={14} />
                            Preview
                        </button>
                    </div>
                    <div className="editor-format-hint">
                        Structured content with headings, lists, links, quotes, and code blocks.
                    </div>
                </div>

                <div className="editor-toolbar">
                    <div className="editor-toolbar-group">
                        <ToolbarButton
                            title="Paragraph"
                            active={editor.isActive('paragraph')}
                            onClick={() => editor.chain().focus().setParagraph().run()}
                        >
                            <Pilcrow size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Heading 1"
                            active={editor.isActive('heading', { level: 1 })}
                            onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
                        >
                            <Heading1 size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Heading 2"
                            active={editor.isActive('heading', { level: 2 })}
                            onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
                        >
                            <Heading2 size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Heading 3"
                            active={editor.isActive('heading', { level: 3 })}
                            onClick={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}
                        >
                            <Heading3 size={16} />
                        </ToolbarButton>
                    </div>

                    <div className="editor-toolbar-divider" />

                    <div className="editor-toolbar-group">
                        <ToolbarButton
                            title="Bold"
                            active={editor.isActive('bold')}
                            onClick={() => editor.chain().focus().toggleBold().run()}
                        >
                            <Bold size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Italic"
                            active={editor.isActive('italic')}
                            onClick={() => editor.chain().focus().toggleItalic().run()}
                        >
                            <Italic size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Strikethrough"
                            active={editor.isActive('strike')}
                            onClick={() => editor.chain().focus().toggleStrike().run()}
                        >
                            <Strikethrough size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Inline code"
                            active={editor.isActive('code')}
                            onClick={() => editor.chain().focus().toggleCode().run()}
                        >
                            <Code size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Add or edit link"
                            active={editor.isActive('link')}
                            onClick={openLinkPanel}
                        >
                            <Link2 size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Remove link"
                            disabled={!editor.isActive('link')}
                            onClick={removeLink}
                        >
                            <Unlink2 size={16} />
                        </ToolbarButton>
                    </div>

                    <div className="editor-toolbar-divider" />

                    <div className="editor-toolbar-group">
                        <ToolbarButton
                            title="Bullet list"
                            active={editor.isActive('bulletList')}
                            onClick={() => editor.chain().focus().toggleBulletList().run()}
                        >
                            <List size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Numbered list"
                            active={editor.isActive('orderedList')}
                            onClick={() => editor.chain().focus().toggleOrderedList().run()}
                        >
                            <ListOrdered size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Increase list level"
                            disabled={!editor.can().sinkListItem('listItem')}
                            onClick={() => editor.chain().focus().sinkListItem('listItem').run()}
                        >
                            <ListOrdered size={16} className="rotate-90" />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Decrease list level"
                            disabled={!editor.can().liftListItem('listItem')}
                            onClick={() => editor.chain().focus().liftListItem('listItem').run()}
                        >
                            <Outdent size={16} />
                        </ToolbarButton>
                    </div>

                    <div className="editor-toolbar-divider" />

                    <div className="editor-toolbar-group">
                        <ToolbarButton
                            title="Quote"
                            active={editor.isActive('blockquote')}
                            onClick={() => editor.chain().focus().toggleBlockquote().run()}
                        >
                            <Quote size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Code block"
                            active={editor.isActive('codeBlock')}
                            onClick={() => editor.chain().focus().toggleCodeBlock().run()}
                        >
                            <Code size={16} className="stroke-[2.25]" />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Divider"
                            onClick={() => editor.chain().focus().setHorizontalRule().run()}
                        >
                            <SeparatorHorizontal size={16} />
                        </ToolbarButton>
                    </div>

                    <div className="editor-toolbar-divider" />

                    <div className="editor-toolbar-group">
                        <ToolbarButton
                            title="Clear formatting"
                            onClick={() => editor.chain().focus().unsetAllMarks().clearNodes().run()}
                        >
                            <RemoveFormatting size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Undo"
                            disabled={!editor.can().undo()}
                            onClick={() => editor.chain().focus().undo().run()}
                        >
                            <Undo2 size={16} />
                        </ToolbarButton>
                        <ToolbarButton
                            title="Redo"
                            disabled={!editor.can().redo()}
                            onClick={() => editor.chain().focus().redo().run()}
                        >
                            <Redo2 size={16} />
                        </ToolbarButton>
                    </div>
                </div>

                {showLinkPanel && (
                    <div className="editor-link-panel">
                        <div className="editor-link-panel-row">
                            <input
                                type="url"
                                value={linkValue}
                                onChange={(event) => setLinkValue(event.target.value)}
                                placeholder="https://example.com"
                                className="editor-link-input"
                            />
                            <button
                                type="button"
                                onClick={applyLink}
                                className="editor-link-action editor-link-action-primary"
                            >
                                Save link
                            </button>
                            <button
                                type="button"
                                onClick={removeLink}
                                className="editor-link-action"
                            >
                                Remove
                            </button>
                            <button
                                type="button"
                                onClick={() => {
                                    setShowLinkPanel(false);
                                    setLinkValue('');
                                }}
                                className="editor-link-action"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                )}

                <div className={`editor-workspace ${viewMode === 'split' ? 'editor-workspace-split' : ''}`}>
                    {viewMode !== 'preview' && (
                        <div className="editor-pane">
                            {editorStats.isEmpty && (
                                <div className="editor-placeholder">
                                    {placeholder}
                                </div>
                            )}
                            <div className="editor-surface">
                                <EditorContent editor={editor} />
                            </div>
                        </div>
                    )}

                    {viewMode !== 'write' && (
                        <div className="editor-preview-pane">
                            <div className="editor-preview-header">Preview</div>
                            <div className="editor-preview-body">
                                {editorStats.isEmpty ? (
                                    <div className="editor-preview-empty">
                                        Your formatted content will appear here.
                                    </div>
                                ) : (
                                    <div
                                        className="editor-preview-content ProseMirror"
                                        dangerouslySetInnerHTML={{ __html: value }}
                                    />
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </div>

            {error && <p className="mt-2 text-sm text-red-500">{error}</p>}
        </div>
    );
}
