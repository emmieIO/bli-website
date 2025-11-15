import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import { useEffect } from 'react';
import './RichTextEditor.css';

interface RichTextEditorProps {
    value: string;
    onChange: (value: string) => void;
    label?: string;
    error?: string;
    required?: boolean;
    placeholder?: string;
}

export default function RichTextEditor({
    value,
    onChange,
    label,
    error,
    required,
    placeholder = 'Start typing...',
}: RichTextEditorProps) {
    const editor = useEditor({
        extensions: [StarterKit],
        content: value,
        editorProps: {
            attributes: {
                class: 'editor-content',
            },
        },
        onUpdate: ({ editor }) => {
            onChange(editor.getHTML());
        },
    });

    // Update editor content when value changes externally
    useEffect(() => {
        if (editor && value !== editor.getHTML()) {
            editor.commands.setContent(value);
        }
    }, [value, editor]);

    if (!editor) {
        return null;
    }

    return (
        <div className="relative">
            {label && (
                <label className="block text-sm font-medium text-gray-700 mb-1 font-lato">
                    {label}
                    {required && <span className="text-red-500 ml-1">*</span>}
                </label>
            )}

            {/* Toolbar */}
            <div className="border border-gray-300 rounded-t-lg bg-gray-50 px-2 py-1 flex flex-wrap gap-1">
                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleBold().run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('bold')
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Bold"
                >
                    <i className="fas fa-bold"></i>
                </button>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleItalic().run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('italic')
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Italic"
                >
                    <i className="fas fa-italic"></i>
                </button>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleStrike().run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('strike')
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Strikethrough"
                >
                    <i className="fas fa-strikethrough"></i>
                </button>

                <div className="w-px bg-gray-300 mx-1"></div>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('heading', { level: 2 })
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Heading 2"
                >
                    H2
                </button>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('heading', { level: 3 })
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Heading 3"
                >
                    H3
                </button>

                <div className="w-px bg-gray-300 mx-1"></div>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleBulletList().run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('bulletList')
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Bullet List"
                >
                    <i className="fas fa-list-ul"></i>
                </button>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleOrderedList().run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('orderedList')
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Numbered List"
                >
                    <i className="fas fa-list-ol"></i>
                </button>

                <div className="w-px bg-gray-300 mx-1"></div>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().toggleBlockquote().run()}
                    className={`px-3 py-1 rounded text-sm font-medium transition ${
                        editor.isActive('blockquote')
                            ? 'bg-primary text-white'
                            : 'bg-white hover:bg-gray-100 text-gray-700'
                    }`}
                    title="Quote"
                >
                    <i className="fas fa-quote-right"></i>
                </button>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().setHorizontalRule().run()}
                    className="px-3 py-1 rounded text-sm font-medium bg-white hover:bg-gray-100 text-gray-700 transition"
                    title="Horizontal Line"
                >
                    <i className="fas fa-minus"></i>
                </button>

                <div className="w-px bg-gray-300 mx-1"></div>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().undo().run()}
                    disabled={!editor.can().undo()}
                    className="px-3 py-1 rounded text-sm font-medium bg-white hover:bg-gray-100 text-gray-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Undo"
                >
                    <i className="fas fa-undo"></i>
                </button>

                <button
                    type="button"
                    onClick={() => editor.chain().focus().redo().run()}
                    disabled={!editor.can().redo()}
                    className="px-3 py-1 rounded text-sm font-medium bg-white hover:bg-gray-100 text-gray-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Redo"
                >
                    <i className="fas fa-redo"></i>
                </button>
            </div>

            {/* Editor Content */}
            <div
                className={`border border-t-0 rounded-b-lg bg-white ${
                    error ? 'border-red-500' : 'border-gray-300'
                }`}
            >
                <EditorContent editor={editor} />
            </div>

            {error && <p className="text-sm text-red-500 mt-1 font-lato">{error}</p>}
        </div>
    );
}
