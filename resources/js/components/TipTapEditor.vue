<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import { Button } from '@/components/ui/button';
import { watch } from 'vue';
import {
    Bold,
    Italic,
    Strikethrough,
    Code,
    List,
    ListOrdered,
    Link as LinkIcon,
    Heading1,
    Heading2,
    Heading3,
    Undo,
    Redo,
    Quote,
} from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        modelValue: string;
        placeholder?: string;
    }>(),
    {
        placeholder: 'Commencez à écrire...',
    }
);

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [1, 2, 3],
            },
        }),
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: 'text-primary underline',
            },
        }),
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-sm max-w-none focus:outline-none min-h-[200px] p-4',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

// Sync external changes to editor
watch(
    () => props.modelValue,
    (value) => {
        const isSame = editor.value?.getHTML() === value;
        if (!isSame && editor.value) {
            editor.value.commands.setContent(value || '', false);
        }
    }
);

const setLink = () => {
    if (!editor.value) return;

    const previousUrl = editor.value.getAttributes('link').href;
    const url = window.prompt('URL', previousUrl);

    // cancelled
    if (url === null) {
        return;
    }

    // empty
    if (url === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
        return;
    }

    // update link
    editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};
</script>

<template>
    <div v-if="editor" class="border rounded-lg bg-background">
        <!-- Toolbar -->
        <div class="border-b p-2 flex gap-1 flex-wrap bg-muted/50">
            <!-- Headings -->
            <Button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                :variant="editor.isActive('heading', { level: 1 }) ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Heading1 class="h-4 w-4" />
            </Button>
            <Button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                :variant="editor.isActive('heading', { level: 2 }) ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Heading2 class="h-4 w-4" />
            </Button>
            <Button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                :variant="editor.isActive('heading', { level: 3 }) ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Heading3 class="h-4 w-4" />
            </Button>

            <div class="w-px h-8 bg-border" />

            <!-- Text formatting -->
            <Button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                :variant="editor.isActive('bold') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Bold class="h-4 w-4" />
            </Button>
            <Button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                :variant="editor.isActive('italic') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Italic class="h-4 w-4" />
            </Button>
            <Button
                type="button"
                @click="editor.chain().focus().toggleStrike().run()"
                :variant="editor.isActive('strike') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Strikethrough class="h-4 w-4" />
            </Button>
            <Button
                type="button"
                @click="editor.chain().focus().toggleCode().run()"
                :variant="editor.isActive('code') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Code class="h-4 w-4" />
            </Button>

            <div class="w-px h-8 bg-border" />

            <!-- Lists -->
            <Button
                type="button"
                @click="editor.chain().focus().toggleBulletList().run()"
                :variant="editor.isActive('bulletList') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <List class="h-4 w-4" />
            </Button>
            <Button
                type="button"
                @click="editor.chain().focus().toggleOrderedList().run()"
                :variant="editor.isActive('orderedList') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <ListOrdered class="h-4 w-4" />
            </Button>

            <div class="w-px h-8 bg-border" />

            <!-- Quote -->
            <Button
                type="button"
                @click="editor.chain().focus().toggleBlockquote().run()"
                :variant="editor.isActive('blockquote') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <Quote class="h-4 w-4" />
            </Button>

            <!-- Link -->
            <Button
                type="button"
                @click="setLink"
                :variant="editor.isActive('link') ? 'default' : 'ghost'"
                size="sm"
                class="h-8 px-2"
            >
                <LinkIcon class="h-4 w-4" />
            </Button>

            <div class="w-px h-8 bg-border" />

            <!-- History -->
            <Button
                type="button"
                @click="editor.chain().focus().undo().run()"
                :disabled="!editor.can().undo()"
                variant="ghost"
                size="sm"
                class="h-8 px-2"
            >
                <Undo class="h-4 w-4" />
            </Button>
            <Button
                type="button"
                @click="editor.chain().focus().redo().run()"
                :disabled="!editor.can().redo()"
                variant="ghost"
                size="sm"
                class="h-8 px-2"
            >
                <Redo class="h-4 w-4" />
            </Button>
        </div>

        <!-- Editor Content -->
        <EditorContent :editor="editor" />
    </div>
</template>

<style>
/* Tiptap editor styles */
.ProseMirror {
    outline: none;
}

.ProseMirror p.is-editor-empty:first-child::before {
    color: hsl(var(--muted-foreground));
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}

.ProseMirror h1 {
    font-size: 1.875rem;
    font-weight: 700;
    line-height: 2.25rem;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.ProseMirror h2 {
    font-size: 1.5rem;
    font-weight: 600;
    line-height: 2rem;
    margin-top: 1.25rem;
    margin-bottom: 0.75rem;
}

.ProseMirror h3 {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.75rem;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.ProseMirror ul,
.ProseMirror ol {
    padding-left: 1.5rem;
    margin: 0.5rem 0;
}

.ProseMirror ul {
    list-style-type: disc;
}

.ProseMirror ol {
    list-style-type: decimal;
}

.ProseMirror li {
    margin: 0.25rem 0;
}

.ProseMirror code {
    background-color: hsl(var(--muted));
    border-radius: 0.25rem;
    padding: 0.125rem 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}

.ProseMirror pre {
    background-color: hsl(var(--muted));
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    margin: 0.5rem 0;
    overflow-x: auto;
}

.ProseMirror pre code {
    background-color: transparent;
    padding: 0;
}

.ProseMirror blockquote {
    border-left: 3px solid hsl(var(--border));
    padding-left: 1rem;
    margin: 0.5rem 0;
    font-style: italic;
    color: hsl(var(--muted-foreground));
}

.ProseMirror a {
    color: hsl(var(--primary));
    text-decoration: underline;
    cursor: pointer;
}

.ProseMirror a:hover {
    text-decoration: none;
}
</style>
