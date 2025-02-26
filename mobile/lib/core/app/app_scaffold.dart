import 'package:calamitech/config/theme/app_theme.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

import '../../constants/route_constants.dart';
import 'cubit/navigation_cubit.dart';

class AppScaffold extends StatelessWidget {
  final Widget child;

  const AppScaffold({super.key, required this.child});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<NavigationCubit, int>(
      builder: (context, selectedIndex) {
        return Scaffold(
          appBar: _buildAppBar(selectedIndex),
          body: child,
          bottomNavigationBar: _buildBottomAppBar(context, selectedIndex),
          floatingActionButtonLocation: FloatingActionButtonLocation.endDocked,
          floatingActionButton: _buildSOSFAB(context),
        );
      },
    );
  }

  BottomAppBar _buildBottomAppBar(BuildContext context, int selectedIndex) {
    return BottomAppBar(
      color: Colors.grey[200],
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        mainAxisSize: MainAxisSize.min,
        children: [
          Expanded(
            child: Row(
              spacing: 20,
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: <Widget>[
                _buildNavBarItem(context, Icons.home, 'Home',
                    RouteConstants.home, 0, selectedIndex),
                _buildNavBarItem(context, Icons.report, 'Report',
                    RouteConstants.report, 1, selectedIndex),
                _buildNavBarItem(context, Icons.person, 'Profile',
                    RouteConstants.profile, 2, selectedIndex),
                // Space for FAB
              ],
            ),
          ),
          const SizedBox(width: 80),
        ],
      ),
    );
  }
}

AppBar _buildAppBar(int selectedIndex) {
  String title;
  switch (selectedIndex) {
    case 0:
      title = 'Home';
      break;
    case 1:
      title = 'Report';
      break;
    case 2:
      title = 'Profile';
      break;
    case 3:
      title = 'Emergency SOS';
    default:
      title = 'Home';
  }

  return AppBar(
    centerTitle: true,
    backgroundColor: AppTheme.primaryColor,
    title: Text(title, style: const TextStyle(color: Colors.white)),
  );
}

FloatingActionButton _buildSOSFAB(BuildContext context) {
  return FloatingActionButton(
    onPressed: () {
      if (GoRouter.of(context).routeInformationProvider.value.uri !=
          RouteConstants.sos) {
        context.read<NavigationCubit>().selectTab(3);
        context.go(RouteConstants.sos);
      }
    },
    backgroundColor: Colors.red,
    child: const Icon(Icons.sos),
  );
}

Widget _buildNavBarItem(BuildContext context, IconData icon, String label,
    String route, int index, int selectedIndex) {
  return GestureDetector(
    onTap: () {
      context.read<NavigationCubit>().selectTab(index);
      context.go(route);
    },
    child: Container(
      color: Colors.transparent,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: selectedIndex == index ? AppTheme.primaryColor : Colors.grey),
          Text(
            label,
            style: TextStyle(
              color: selectedIndex == index ? AppTheme.primaryColor : Colors.grey,
            ),
          ),
        ],
      ),
    ),
  );
}
