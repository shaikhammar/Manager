'use client';

import {
    ColumnDef,
    ColumnFiltersState,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    useReactTable,
} from '@tanstack/react-table';

import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import { Fragment, useState } from 'react';
import { Input } from '../ui/input';

interface BusinessDataTableProps<TData, TValue> {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
}

export function BusinessDataTable<TData, TValue>({
    columns,
    data,
}: BusinessDataTableProps<TData, TValue>) {
    const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        onColumnFiltersChange: setColumnFilters,
        getFilteredRowModel: getFilteredRowModel(),
        state: {
            columnFilters,
        },
    });

    const rows = table.getRowModel().rows;
    const groupedRows = rows.reduce(
        (groups, row) => {
            const name = (row.getValue('name') as string) || '';
            const letter = name ? name.charAt(0).toUpperCase() : '#';
            if (!groups[letter]) {
                groups[letter] = [];
            }
            groups[letter].push(row);
            return groups;
        },
        {} as Record<string, typeof rows>,
    );

    const sortedLetters = Object.keys(groupedRows).sort();

    return (
        <div>
            <div className="flex items-center py-4">
                <Input
                    placeholder="Filter name..."
                    value={
                        (table.getColumn('name')?.getFilterValue() as string) ??
                        ''
                    }
                    onChange={(event) =>
                        table
                            .getColumn('name')
                            ?.setFilterValue(event.target.value)
                    }
                    className="max-w-sm"
                />
            </div>
            <div className="overflow-hidden rounded-md border">
                <Table>
                    {/* <TableHeader>
          {table.getHeaderGroups().map((headerGroup) => (
            <TableRow key={headerGroup.id}>
            {headerGroup.headers.map((header) => {
                return (
                    <TableHead key={header.id}>
                    {header.isPlaceholder
                    ? null
                    : flexRender(
                        header.column.columnDef.header,
                        header.getContext()
                        )}
                        </TableHead>
                        )
                        })}
                        </TableRow>
                        ))}
                        </TableHeader> */}
                    <TableBody>
                        {rows?.length ? (
                            sortedLetters.map((letter) => (
                                <Fragment key={letter}>
                                    <TableRow className="bg-muted/50 hover:bg-muted/50">
                                        <TableCell
                                            colSpan={columns.length}
                                            className="py-2 pl-4 font-semibold"
                                        >
                                            {letter}
                                        </TableCell>
                                    </TableRow>
                                    {groupedRows[letter].map((row) => (
                                        <TableRow
                                            key={row.id}
                                            data-state={
                                                row.getIsSelected() &&
                                                'selected'
                                            }
                                        >
                                            {row
                                                .getVisibleCells()
                                                .map((cell) => (
                                                    <TableCell
                                                        key={cell.id}
                                                        className="px-10 py-2 hover:bg-muted/50"
                                                    >
                                                        {flexRender(
                                                            cell.column
                                                                .columnDef.cell,
                                                            cell.getContext(),
                                                        )}
                                                    </TableCell>
                                                ))}
                                        </TableRow>
                                    ))}
                                </Fragment>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    No results.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
        </div>
    );
}
