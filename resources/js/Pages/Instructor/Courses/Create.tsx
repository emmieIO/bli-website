import { Head, usePage } from '@inertiajs/react';
import CourseCreateForm from '@/Components/CourseCreateForm';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface Category {
    id: number;
    name: string;
}

interface Level {
    value: string;
    label: string;
}

interface CreateCourseProps {
    categories: Category[];
    levels: Level[];
}

export default function CreateCourse({ categories, levels }: CreateCourseProps) {
    const { sideLinks } = usePage().props as any;

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Create New Course" />

            <CourseCreateForm
                categories={categories}
                levels={levels}
                submitRoute={route('instructor.courses.store')}
                backRoute={route('instructor.courses.index')}
                eyebrow="Instructor Workspace"
                heading="Create a New Course"
                description="Shape the learner experience from the first screen with stronger copy, calmer visuals, and a more intentional setup flow."
                priceLabel="Price (USD)"
                priceHint="Set a clear USD price for paid courses. Leave the course marked free if pricing comes later."
            />
        </DashboardLayout>
    );
}
