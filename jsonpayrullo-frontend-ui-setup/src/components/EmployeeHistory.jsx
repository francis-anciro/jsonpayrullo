import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { ArrowLeft, Loader2, History, Clock, User, FileEdit } from 'lucide-react';

const EmployeeHistory = () => {
    const { code } = useParams();
    const [isMounted, setIsMounted] = useState(false);
    const [isLoading, setIsLoading] = useState(true);
    const [logs, setLogs] = useState([]);

    useEffect(() => {
        setIsMounted(true);
        fetchHistory();
    }, [code]);

    const fetchHistory = async () => {
        try {
            const response = await fetch(`http://localhost/JSONPayrullo/EmployeeList/history/${code}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });
            const result = await response.json();

            if (response.ok && result.status === 'success') {
                // Map DB fields to what the UI renders
                const mapped = result.data.map(log => ({
                    id:      log.Log_ID,
                    date:    log.changed_at,
                    admin:   log.changed_by_name,
                    action:  `Updated ${log.field_name.replace(/_/g, ' ')}`,
                    details: `Changed ${log.field_name.replace(/_/g, ' ')} from "${log.old_value}" to "${log.new_value}"`
                }));
                setLogs(mapped);
            } else {
                setLogs([]);
            }
        } catch (error) {
            console.error("Failed to fetch history:", error);
            setLogs([]);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-hidden">

            {/* Background */}
            <div className="fixed inset-0 z-0 pointer-events-none opacity-50" style={{ backgroundImage: 'radial-gradient(#666666 1px, transparent 1px)', backgroundSize: '24px 24px' }}></div>

            {/* --- RIGHT & LEFT LIGHTS (CYAN) --- */}
            <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-cyan-500/20 to-transparent blur-3xl pointer-events-none transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
            <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-cyan-500/20 to-transparent blur-3xl pointer-events-none transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

            <div className={`relative z-10 w-full max-w-4xl flex flex-col gap-6 transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>

                {/* Header */}
                <div className="flex items-center gap-4 mb-4">
                    <Link to="/employees" className="p-3 text-zinc-400 hover:text-cyan-400 hover:bg-cyan-500/10 hover:border-cyan-500/50 hover:shadow-[0_0_15px_rgba(6,182,212,0.2)] rounded-xl transition-all duration-300 bg-[#121212] border border-zinc-800 shadow-lg group">
                        <ArrowLeft size={24} className="group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div className="flex flex-col">
                        <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg flex items-center gap-3">
                            <History className="text-cyan-500" size={28} /> Audit Log
                        </h1>
                        <p className="text-cyan-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
                            Record History for <span className="text-white bg-zinc-800/50 px-2 py-0.5 rounded border border-zinc-700">{code}</span>
                        </p>
                    </div>
                </div>

                {/* Timeline Container */}
                <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">

                    {/* Subtle top edge glow for depth */}
                    <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>

                    {/* Center Timeline Line (Gradient faded at bottom) */}
                    <div className="absolute left-[3.2rem] md:left-[4.2rem] top-12 bottom-10 w-px bg-gradient-to-b from-zinc-700 via-zinc-800 to-transparent"></div>

                    <div className="flex flex-col gap-8">
                        {logs.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-10 gap-3">
                                <History className="text-zinc-600" size={40} />
                                <p className="text-zinc-500 text-center font-bold tracking-widest uppercase">No history records found.</p>
                            </div>
                        ) : (
                            logs.map((log, index) => (
                                <div key={log.id} className="relative flex items-start gap-6 md:gap-8 group">

                                    {/* Timeline Dot (Glowing on hover) */}
                                    <div className="w-9 h-9 rounded-full bg-[#121212] border-[3px] border-zinc-700 group-hover:border-cyan-500 flex items-center justify-center z-10 transition-all duration-300 shrink-0 mt-2 shadow-lg group-hover:shadow-[0_0_15px_rgba(6,182,212,0.4)] group-hover:scale-110">
                                        <div className="w-2.5 h-2.5 rounded-full bg-zinc-500 group-hover:bg-cyan-400 transition-colors"></div>
                                    </div>

                                    {/* Log Content Card (Premium Styling) */}
                                    <div className="flex-1 bg-gradient-to-br from-[#18181b] to-[#0a0a0a] border border-zinc-800/80 border-l-[4px] border-l-zinc-700 group-hover:border-l-cyan-500 rounded-2xl p-5 md:p-6 shadow-inner transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-[0_12px_30px_-10px_rgba(6,182,212,0.15)] relative overflow-hidden">

                                        {/* Subtle hover background light */}
                                        <div className="absolute top-0 right-0 w-32 h-32 bg-cyan-500/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

                                        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4 border-b border-zinc-800/60 pb-4 relative z-10">
                                            <div className="flex items-center gap-3">
                                                <div className="p-2 bg-zinc-800/50 group-hover:bg-cyan-500/10 rounded-lg transition-colors border border-zinc-700/50 group-hover:border-cyan-500/30">
                                                    <FileEdit className="text-zinc-400 group-hover:text-cyan-400 transition-colors" size={16} />
                                                </div>
                                                <h3 className="text-white font-black uppercase tracking-wider text-sm md:text-base group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-cyan-400 group-hover:to-blue-500 transition-all duration-300">
                                                    {log.action}
                                                </h3>
                                            </div>

                                            {/* Premium Pill Badges for Metadata */}
                                            <div className="flex flex-wrap items-center gap-2 text-[10px] md:text-xs font-bold uppercase tracking-widest text-zinc-400">
                        <span className="flex items-center gap-1.5 bg-[#0a0a0a] border border-zinc-800 px-3 py-1.5 rounded-full shadow-inner">
                          Changed by: <User size={12} className="text-cyan-600" /> {log.admin}
                        </span>
                                                <span className="flex items-center gap-1.5 bg-[#0a0a0a] border border-zinc-800 px-3 py-1.5 rounded-full shadow-inner">
                          <Clock size={12} className="text-cyan-600" /> {log.date}
                        </span>
                                            </div>
                                        </div>

                                        <p className="text-zinc-300 text-sm font-medium tracking-wide leading-relaxed relative z-10 pl-1 group-hover:text-zinc-200 transition-colors">
                                            {log.details}
                                        </p>

                                    </div>
                                </div>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default EmployeeHistory;