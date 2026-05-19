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
                submitRoute={route('admin.courses.store')}
                backRoute={route('admin.courses.index')}
                eyebrow="Course Operations"
                heading="Create a New Course"
                description="Set up a course with clearer structure, better learner-facing copy, and cleaner publishing details before you move into the builder."
                priceLabel="Price (NGN)"
                priceHint="Enter the full enrolment price in naira for paid courses."
            />
        </DashboardLayout>
    );
}
