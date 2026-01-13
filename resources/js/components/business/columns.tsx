'use client';

import { switchMethod } from '@/routes/business';
import { Business } from '@/types';
import { Link } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';

// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.

export const columns: ColumnDef<Business>[] = [
    {
        accessorKey: 'name',
        header: 'Name',
        cell: ({ row }) => (
            <Link
                href={switchMethod(row.original.id)}
                className="cursor-pointer font-semibold text-blue-500 transition-colors duration-200 ease-in-out hover:text-blue-800 hover:underline"
            >
                {row.getValue('name')}
            </Link>
        ),
    },
];
