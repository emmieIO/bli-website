import { Head } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { GraduationCap } from 'lucide-react';

interface Certificate {
    certificate_number: string;
    completion_date: string;
    certificate_data: {
        student_name: string;
        course_title: string;
        instructor_name: string;
        completion_date: string;
        course_duration: string;
    };
    certificate_url: string;
}

interface ViewCertificateProps {
    certificate: Certificate;
}

export default function ViewCertificate({ certificate }: ViewCertificateProps) {
    return (
        <DashboardLayout>
            <Head title="View Certificate" />

            <div className="py-12 bg-gray-100">
                <div className="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-2xl sm:rounded-lg border-8 border-primary-500" style={{ backgroundImage: "url('/images/pattern-bg.jpg')", backgroundSize: 'cover' }}>
                        <div className="p-8 md:p-12 bg-white/90 border-b border-gray-200">
                            <div className="flex justify-between items-start mb-8">
                                <div className="flex-shrink-0">
                                    <img src="/images/logo.jpg" alt="Logo" className="h-24" />
                                </div>
                                <div className="text-right">
                                    <p className="text-sm text-gray-500">Certificate Number</p>
                                    <p className="text-lg font-mono">{certificate.certificate_number}</p>
                                </div>
                            </div>

                            <div className="text-center mb-12">
                                <h1 className="text-6xl font-bold text-gray-800" style={{ fontFamily: 'serif' }}>
                                    Certificate of Completion
                                </h1>
                                <p className="text-xl text-gray-600 mt-4">This is to certify that</p>
                            </div>

                            <div className="text-center mb-12">
                                <p className="text-7xl font-extrabold text-primary" style={{ fontFamily: 'cursive' }}>
                                    {certificate.certificate_data.student_name}
                                </p>
                                <div className="w-2/3 mx-auto my-6 border-t-4 border-primary-300"></div>
                                <p className="text-xl text-gray-600">has successfully completed the course</p>
                                <p className="text-4xl font-bold text-gray-800 mt-2">
                                    {certificate.certificate_data.course_title}
                                </p>
                            </div>

                            <div className="flex justify-between items-end text-center mt-16">
                                <div>
                                    <p className="text-xl font-semibold border-t-2 border-gray-400 pt-2 px-8">
                                        {certificate.certificate_data.instructor_name}
                                    </p>
                                    <p className="text-md text-gray-500 mt-1">Instructor</p>
                                </div>
                                <div className="flex-shrink-0">
                                    <GraduationCap className="h-32 w-32 text-primary" />
                                </div>
                                <div>
                                    <p className="text-xl font-semibold border-t-2 border-gray-400 pt-2 px-8">
                                        {certificate.certificate_data.completion_date}
                                    </p>
                                    <p className="text-md text-gray-500 mt-1">Completion Date</p>
                                </div>
                            </div>
                        </div>
                        <div className="p-6 bg-gray-50/90 border-t border-gray-200 text-center">
                            <a
                                href={route('certificates.download', { certificate: certificate.certificate_number })}
                                className="inline-flex items-center px-8 py-4 bg-primary border border-transparent rounded-md font-semibold text-md text-white uppercase tracking-widest hover:bg-primary/90 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                Download Certificate
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
