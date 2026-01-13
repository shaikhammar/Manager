"use client"

import { ColumnDef } from "@tanstack/react-table"
import { Button } from "../ui/button"
import { Link } from "@inertiajs/react"
import { switchMethod } from "@/routes/business"
import { Checkbox } from "../ui/checkbox"

// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.
export type Business = {
  id: number
  name: string
  code: string
  country: string
  currency: string
}

export const columns: ColumnDef<Business>[] = [
    {
      accessorKey: "name",
      header: "Name",
      cell: ({ row }) => (
        <Link href={switchMethod(row.original.id)} className="hover:underline cursor-pointer text-blue-500 hover:text-blue-800 transition-colors duration-200 ease-in-out font-semibold">
          {row.getValue("name")}
        </Link>
      ),
    },
]