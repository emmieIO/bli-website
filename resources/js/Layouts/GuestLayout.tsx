
import { Link, usePage } from '@inertiajs/react';

import React, { PropsWithChildren, useState, useRef, useEffect } from 'react';

import { PageProps } from '@/types';

import { ToastContainer, useToastNotifications } from '@/Components/Toast';



export default function GuestLayout({ children }: PropsWithChildren) {

    const { auth } = usePage<PageProps>().props;

    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    const [joinUsDropdownOpen, setJoinUsDropdownOpen] = useState(false);

    const joinUsDropdownRef = useRef<HTMLLIElement>(null);



    // Close dropdown when clicking outside

    useEffect(() => {

        function handleClickOutside(event: MouseEvent) {

            if (joinUsDropdownRef.current && !joinUsDropdownRef.current.contains(event.target as Node)) {

                setJoinUsDropdownOpen(false);

            }

        }



        document.addEventListener("mousedown", handleClickOutside);

        return () => {

            document.removeEventListener("mousedown", handleClickOutside);

        };

    }, [joinUsDropdownRef]);



    // Toast notifications

    useToastNotifications();



    return (

        <div className="overflow-x-hidden min-h-screen flex flex-col">

            {/* Toast Notifications */}

            <ToastContainer />

            {/* Navbar */}

            <nav className="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-100">

                <div className="max-w-7xl mx-auto flex justify-between items-center py-3 px-4 md:px-8">

                    {/* Logo */}

                    <Link href={route('homepage')} className="flex items-center space-x-3 group">

                        <div className="relative">

                            <img src="/images/logo.jpg" alt="BLI Logo" className="w-14 h-14 rounded-xl object-cover shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-105" />

                            <div className="absolute -top-1 -right-1 w-3 h-3 rounded-full opacity-80 group-hover:opacity-100 transition-opacity duration-300" style={{ backgroundColor: '#00a651' }}></div>

                        </div>

                        <div className="hidden sm:block">

                            <div className="flex items-center gap-2">

                                <span className="text-xl font-bold tracking-tight font-montserrat leading-tight" style={{ color: '#002147' }}>

                                    Beacon Leadership

                                </span>

                                <i className="fas fa-lightbulb text-sm group-hover:text-yellow-500 transition-colors duration-300" style={{ color: '#00a651' }}></i>

                            </div>

                            <span className="text-sm font-semibold tracking-wide font-lato block" style={{ color: '#00a651' }}>

                                Institute

                            </span>

                        </div>

                    </Link>



                    {/* Desktop Navigation */}

                    <ul className="hidden lg:flex gap-x-8 items-center font-semibold text-sm">

                        <li>

                            <Link href={route('homepage')} className="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group" style={{ color: '#002147' }}>

                                <i className="fas fa-home text-sm group-hover:scale-110 transition-transform duration-300" style={{ color: '#00a651' }}></i>

                                <span>Home</span>

                                <span className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full" style={{ backgroundColor: '#00a651' }}></span>

                            </Link>

                        </li>

                        <li>

                            <Link href={route('events.index')} className="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group" style={{ color: '#002147' }}>

                                <i className="fas fa-calendar-alt text-sm group-hover:scale-110 transition-transform duration-300" style={{ color: '#00a651' }}></i>

                                <span>Events</span>

                                <span className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full" style={{ backgroundColor: '#00a651' }}></span>

                            </Link>

                        </li>

                        <li>

                            <Link href={route('courses.index')} className="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group" style={{ color: '#002147' }}>

                                <i className="fas fa-graduation-cap text-sm group-hover:scale-110 transition-transform duration-300" style={{ color: '#00a651' }}></i>

                                <span>Courses</span>

                                <span className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full" style={{ backgroundColor: '#00a651' }}></span>

                            </Link>

                        </li>

                        <li>

                            <Link href={route('blog.index')} className="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group" style={{ color: '#002147' }}>

                                <i className="fas fa-blog text-sm group-hover:scale-110 transition-transform duration-300" style={{ color: '#00a651' }}></i>

                                <span>Blog</span>

                                <span className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full" style={{ backgroundColor: '#00a651' }}></span>

                            </Link>

                        </li>

                        <li className="relative" ref={joinUsDropdownRef}>

                            <button onClick={() => setJoinUsDropdownOpen(!joinUsDropdownOpen)} className="flex items-center gap-2 py-2 px-1 transition-all duration-300 group relative dropdown-trigger" style={{ color: '#002147' }}>

                                <i className="fas fa-user-plus text-sm group-hover:scale-110 transition-transform duration-300" style={{ color: '#00a651' }}></i>

                                <span>Join Us</span>

                                <svg xmlns="http://www.w3.org/2000/svg" className={`h-4 w-4 ml-1 transition-transform duration-300 ${joinUsDropdownOpen ? 'rotate-180' : ''}`} fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />

                                </svg>

                                <span className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full" style={{ backgroundColor: '#00a651' }}></span>

                            </button>

                            {joinUsDropdownOpen && (

                                <ul className="absolute left-0 bg-white shadow-xl rounded-xl p-3 mt-3 w-64 border border-gray-100 z-50">

                                    <li>

                                        <Link href={route('instructors.become-an-instructor')} className="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-300 group/item hover:shadow-md" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                            <i className="fas fa-chalkboard-teacher text-sm" style={{ color: '#00a651' }}></i>

                                            <div>

                                                <span className="font-semibold">Become an Instructor</span>

                                                <p className="text-xs text-gray-500 mt-1">Share your expertise with others</p>

                                            </div>

                                        </Link>

                                    </li>

                                    <li>

                                        <Link href={route('become-a-speaker')} className="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-300 group/item hover:shadow-md" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                            <i className="fas fa-microphone text-sm" style={{ color: '#00a651' }}></i>

                                            <div>

                                                <span className="font-semibold">Become a Speaker</span>

                                                <p className="text-xs text-gray-500 mt-1">Inspire through speaking engagements</p>

                                            </div>

                                        </Link>

                                    </li>

                                </ul>

                            )}

                        </li>

                    </ul>



                    {/* Account/Login */}

                    <div className="hidden lg:flex items-center space-x-3">

                        {auth.user ? (

                            <>

                                <Link href={route('user_dashboard')} className="flex items-center gap-2 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105" style={{ backgroundColor: '#002147', color: 'white' }} onMouseOver={e => e.currentTarget.style.backgroundColor = '#1e3a8a'} onMouseOut={e => e.currentTarget.style.backgroundColor = '#002147'}>

                                    <i className="fas fa-user-circle text-lg"></i>

                                    <span>My Account</span>

                                </Link>

                                <Link href={route('logout')} method="post" as="button" className="flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105" style={{ backgroundColor: '#ed1c24', color: 'white' }} onMouseOver={e => e.currentTarget.style.backgroundColor = '#dc2626'} onMouseOut={e => e.currentTarget.style.backgroundColor = '#ed1c24'} title="Logout">

                                    <i className="fas fa-power-off text-sm"></i>

                                </Link>

                            </>

                        ) : (

                            <Link href={route('login')} className="font-semibold px-6 py-2.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105" style={{ backgroundColor: '#00a651', color: 'white' }} onMouseOver={e => e.currentTarget.style.backgroundColor = '#15803d'} onMouseOut={e => e.currentTarget.style.backgroundColor = '#00a651'}>

                                Login

                            </Link>

                        )}

                    </div>



                    {/* Mobile Menu Button */}

                    <button onClick={() => setMobileMenuOpen(!mobileMenuOpen)} className="lg:hidden p-2 rounded-lg transition-colors duration-300 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2" style={{ color: '#002147' }}>

                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />

                        </svg>

                    </button>

                </div>

                {/* Mobile Menu */}

                {mobileMenuOpen && (

                    <div className="lg:hidden bg-white border-t border-gray-200 shadow-lg">

                        <div className="max-w-7xl mx-auto px-4 py-6">

                            <ul className="flex flex-col space-y-2 font-semibold">

                                <li>

                                    <Link href={route('homepage')} className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                        <i className="fas fa-home text-sm" style={{ color: '#00a651' }}></i>

                                        <span>Home</span>

                                    </Link>

                                </li>

                                <li>

                                    <Link href={route('events.index')} className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                        <i className="fas fa-calendar text-sm" style={{ color: '#00a651' }}></i>

                                        <span>Events</span>

                                    </Link>

                                </li>

                                <li>

                                    <Link href={route('courses.index')} className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                        <i className="fas fa-graduation-cap text-sm" style={{ color: '#00a651' }}></i>

                                        <span>Courses</span>

                                    </Link>

                                </li>

                                <li>

                                    <Link href={route('blog.index')} className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                        <i className="fas fa-blog text-sm" style={{ color: '#00a651' }}></i>

                                        <span>Blog</span>

                                    </Link>

                                </li>

                                <li className="pt-2">

                                    <div className="px-4 py-2">

                                        <span className="text-xs font-bold uppercase tracking-wider text-gray-500">Join Us</span>

                                    </div>

                                    <Link href={route('instructors.become-an-instructor')} className="flex items-center gap-3 px-6 py-3 rounded-xl transition-all duration-300 ml-2" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                        <i className="fas fa-chalkboard-teacher text-sm" style={{ color: '#00a651' }}></i>

                                        <span>Become an Instructor</span>

                                    </Link>

                                    <Link href={route('become-a-speaker')} className="flex items-center gap-3 px-6 py-3 rounded-xl transition-all duration-300 ml-2" style={{ color: '#002147' }} onMouseOver={e => e.currentTarget.style.backgroundColor = 'rgba(0, 166, 81, 0.1)'} onMouseOut={e => e.currentTarget.style.backgroundColor = 'transparent'}>

                                        <i className="fas fa-microphone text-sm" style={{ color: '#00a651' }}></i>

                                        <span>Become a Speaker</span>

                                    </Link>

                                </li>

                                <li className="pt-4 mt-4 border-t border-gray-200">

                                    {auth.user ? (

                                        <div className="flex flex-col gap-3">

                                            <Link href={route('user_dashboard')} className="flex items-center justify-center gap-2 font-semibold px-4 py-3 rounded-xl transition-all duration-300 shadow-md" style={{ backgroundColor: '#002147', color: 'white' }}>

                                                <i className="fas fa-user-circle"></i>

                                                <span>My Account</span>

                                            </Link>

                                            <Link href={route('logout')} method="post" as="button" className="w-full flex items-center justify-center gap-2 font-semibold px-4 py-3 rounded-xl transition-all duration-300 shadow-md" style={{ backgroundColor: '#ed1c24', color: 'white' }}>

                                                <i className="fas fa-power-off"></i>

                                                <span>Logout</span>

                                            </Link>

                                        </div>

                                    ) : (

                                        <Link href={route('login')} className="flex items-center justify-center gap-2 font-semibold px-4 py-3 rounded-xl transition-all duration-300 shadow-md w-full" style={{ backgroundColor: '#00a651', color: 'white' }}>

                                            <i className="fas fa-sign-in-alt"></i>

                                            <span>Login</span>

                                        </Link>

                                    )}

                                </li>

                            </ul>

                        </div>

                    </div>

                )}

            </nav>



            {/* Page Content */}

            <main className="flex-1 min-h-[60vh] w-full px-4 md:px-8 py-8">

                {children}

            </main>



            {/* Footer */}

            <footer className="bg-gray-100 py-8 mt-16 border-t border-gray-200">

                <div className="max-w-7xl mx-auto px-4 md:px-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">

                    <p className="text-center md:text-left text-gray-700 text-sm">

                        &copy; {new Date().getFullYear()}

                        <span className="text-primary font-semibold"> Beacon Leadership Institute</span>.

                        All rights reserved.

                    </p>

                    <div className="flex items-center space-x-4">

                        <span className="text-gray-700">Connect:</span>

                        <div className="flex space-x-3">

                            <a href="#" className="hover:text-primary transition"><i className="fab fa-facebook text-xl"></i></a>

                            <a href="#" className="hover:text-primary transition"><i className="fab fa-instagram text-xl"></i></a>

                            <a href="#" className="hover:text-primary transition"><i className="fab fa-twitter text-xl"></i></a>

                        </div>

                    </div>

                </div>

            </footer>

        </div>

    );

}


