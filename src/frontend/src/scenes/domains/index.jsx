// import AddCircleOutlineOutlinedIcon from "@mui/icons-material/AddCircleOutlineOutlined";
// import DeleteOutlinedIcon from "@mui/icons-material/DeleteOutlined";
import { Box, useTheme } from "@mui/material";
// import { DataGrid } from "@mui/x-data-grid";
import Header from "../../components/Header";
import { mockDataDomain } from "../../data/mockData";
import { tokens } from "../../theme";

// const Domain = () => {
//   const theme = useTheme();
//   const colors = tokens(theme.palette.mode);

//   return (
//     <Box m="15px">
//       <Header title="Домены" subtitle="Управление доменами" />
//       <Box display="flex">
//         <IconButton>
//           <AddCircleOutlineOutlinedIcon />
//         </IconButton>
//         <IconButton>
//           <DeleteOutlinedIcon />
//         </IconButton>
//       </Box>
//       <Box
//         m="10px 0 0 0"
//         height="75vh"
//         sx={{
//           "& .MuiDataGrid-root": {
//             border: "none",
//           },
//           "& .MuiDataGrid-cell": {
//             borderBottom: "none",
//           },
//           "& .name-column--cell": {
//             color: colors.goldAccent[300],
//           },
//           "& .MuiDataGrid-columnHeaders": {
//             backgroundColor: colors.pinkAccent[500],
//             borderBottom: "none",
//           },
//           "& .MuiDataGrid-virtualScroller": {
//             backgroundColor: colors.primary[400],
//           },
//           "& .MuiDataGrid-footerContainer": {
//             borderTop: "none",
//             backgroundColor: colors.pinkAccent[500],
//           },
//           "& .MuiCheckbox-root": {
//             color: `${colors.goldAccent[300]} !important`,
//           },
//         }}
//       >
//         <DataGrid checkboxSelection rows={mockDataDomain} columns={colums} />
//       </Box>
//     </Box>
//   );
// };

// export default Domain;

import AddIcon from "@mui/icons-material/Add";
import CancelIcon from "@mui/icons-material/Close";
import DeleteIcon from "@mui/icons-material/DeleteOutlined";
import EditIcon from "@mui/icons-material/Edit";
import SaveIcon from "@mui/icons-material/Save";
import Button from "@mui/material/Button";
import {
  DataGrid,
  GridActionsCellItem,
  GridRowEditStopReasons,
  GridRowModes,
} from "@mui/x-data-grid";
import * as React from "react";


function EditToolbar(props) {
  const { setRows, setRowModesModel } = props;
  const [rows] = React.useState(mockDataDomain);

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const handleClick = () => {
    const maxId = rows.reduce((max, row) => (row.id > max ? row.id : max), 0);
    console.log(maxId);
    const id = maxId + 1;
    setRows((oldRows) => [
      ...oldRows,
      { id, name: "", admin: "", minimum: 0, isNew: true },
    ]);
    setRowModesModel((oldModel) => ({
      ...oldModel,
      [id]: { mode: GridRowModes.Edit, fieldToFocus: "name" },
    }));
  };

  return (
    <Box>
      <Header title="Домены" subtitle="Управление доменами" />
      <Button
        color="primary"
        startIcon={<AddIcon />}
        onClick={handleClick}
        sx={{
          margin: "20px 5px",
          border: "1px solid",
          color: colors.grey[100]
        }}
      >
        Добавить домен
      </Button>
    </Box>
  );
}

const Domain = () => {
  const [rows, setRows] = React.useState(mockDataDomain);
  const [rowModesModel, setRowModesModel] = React.useState({});

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const handleRowEditStop = (params, event) => {
    if (params.reason === GridRowEditStopReasons.rowFocusOut) {
      event.defaultMuiPrevented = true;
    }
  };

  const handleEditClick = (id) => () => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.Edit } });
  };

  const handleSaveClick = (id) => () => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.View } });
  };

  const handleDeleteClick = (id) => () => {
    setRows(rows.filter((row) => row.id !== id));
  };

  const handleCancelClick = (id) => () => {
    setRowModesModel({
      ...rowModesModel,
      [id]: { mode: GridRowModes.View, ignoreModifications: true },
    });

    const editedRow = rows.find((row) => row.id === id);
    if (editedRow.isNew) {
      setRows(rows.filter((row) => row.id !== id));
    }
  };

  const processRowUpdate = (newRow) => {
    const updatedRow = { ...newRow, isNew: false };
    setRows(rows.map((row) => (row.id === newRow.id ? updatedRow : row)));
    return updatedRow;
  };

  const handleRowModesModelChange = (newRowModesModel) => {
    setRowModesModel(newRowModesModel);
  };

  const columns = [
    { field: "id", headerName: "ID" },
    {
      field: "name",
      headerName: "Имя домена",
      flex: 1,
      editable: true,
      cellClassName: "name-column--cell",
    },
    {
      field: "admin",
      headerName: "Владелец",
      type: "string",
      flex: 1,
      editable: true,
    },
    {
      field: "minimum",
      headerName: "Дата последнего изменения",
      type: "number",
      flex: 1,
      editable: true,
    },
    {
      field: "actions",
      type: "actions",
      headerName: "Действия",
      width: 100,
      cellClassName: "actions",
      getActions: ({ id }) => {
        const isInEditMode = rowModesModel[id]?.mode === GridRowModes.Edit;

        if (isInEditMode) {
          return [
            <GridActionsCellItem
              icon={<SaveIcon />}
              label="Save"
              sx={{
                color: "primary.main",
              }}
              onClick={handleSaveClick(id)}
            />,
            <GridActionsCellItem
              icon={<CancelIcon />}
              label="Cancel"
              className="textPrimary"
              onClick={handleCancelClick(id)}
              color="inherit"
            />,
          ];
        }

        return [
          <GridActionsCellItem
            icon={<EditIcon />}
            label="Edit"
            className="textPrimary"
            onClick={handleEditClick(id)}
            color="inherit"
          />,
          <GridActionsCellItem
            icon={<DeleteIcon />}
            label="Delete"
            onClick={handleDeleteClick(id)}
            color="inherit"
          />,
        ];
      },
    },
  ];

  return (
    <Box
      m="15px"
      sx={{
        "& .MuiDataGrid-root": {
          border: colors.goldAccent[300],
        },
        "& .actions": {
          color: "text.secondary",
        },
        "& .textPrimary": {
          color: "text.primary",
        },
        "& .MuiCheckbox-root": {
          color: `${colors.goldAccent[300]} !important`,
        },
      }}
    >
      <DataGrid
        checkboxSelection
        rows={rows}
        columns={columns}
        editMode="row"
        rowModesModel={rowModesModel}
        onRowModesModelChange={handleRowModesModelChange}
        onRowEditStop={handleRowEditStop}
        processRowUpdate={processRowUpdate}
        slots={{
          toolbar: EditToolbar,
        }}
        slotProps={{
          toolbar: { setRows, setRowModesModel },
        }}
      />
    </Box>
  );
};

export default Domain;
