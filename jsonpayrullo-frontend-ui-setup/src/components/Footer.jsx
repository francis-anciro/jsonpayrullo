import React from 'react';
import { Link } from 'react-router-dom';

const Footer = () => {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="w-full border-t border-zinc-800 bg-[#0a0a0a] py-6 px-8 relative z-50">
      <div className="mx-auto flex flex-col items-center justify-between gap-4 md:flex-row">

        {/* Copyright Section */}
        <div className="flex items-center gap-3">
          <h2 className="text-sm font-bold tracking-widest text-white uppercase">
            &copy; {currentYear} JSON PAYRULO
          </h2>
          <span className="hidden h-4 w-px bg-zinc-800 md:block"></span>
          <p className="text-xs font-bold uppercase tracking-widest text-zinc-600">
            All rights reserved.
          </p>
        </div>

        {/* Links Section */}
        <nav className="flex gap-6 text-xs font-bold tracking-widest uppercase text-zinc-500">
          <Link>Privacy Policy</Link>
          <Link>Terms of Service</Link>
          <Link>Support</Link>
        </nav>

      </div>
    </footer>
  );
};

export default Footer;